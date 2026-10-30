// Actual Vue renderers + real PHP projection, synthetic data only. No Laravel/MySQL session.
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';
import os from 'node:os';
import http from 'node:http';
import { spawn, execFileSync } from 'node:child_process';
import { once } from 'node:events';
import { build } from 'vite';
import vue from '@vitejs/plugin-vue';
import tailwind from '@tailwindcss/vite';

const root = process.cwd();
const temporary = fs.mkdtempSync(path.join(os.tmpdir(), 'iches-s104-ui-'));
const output = path.join(root, 'harness/evidence/ICH-S1-04/browser');
fs.mkdirSync(output, { recursive: true });
const php = process.env.ICHES_PHP_EXE ?? path.join(os.homedir(), '.config/herd/bin/php84/php.exe');
const fixture = mode => JSON.parse(execFileSync(php, ['harness/evidence/ICH-S1-04/fixture.php', mode], { encoding: 'utf8' }));
const pages = { unknown: fixture('unknown'), known: fixture('known') };
for (const mode of Object.values(pages)) {
    for (const page of Object.values(mode)) {
        assert.equal(page.sections.length, 7);
        assert.deepEqual([...new Set(page.sections.map(section => section.component_key))].sort(), Object.keys(page.catalog).sort());
        for (const [key, schema] of Object.entries(page.catalog)) {
            assert.deepEqual(page.sections.filter(section => section.component_key === key).map(section => section.variant), schema.variants);
        }
        assert.ok(!JSON.stringify(page).includes('SECRET'));
        assert.ok(!JSON.stringify(page).includes('media_ids'));
    }
}
const source = `
import {createApp,h} from 'vue';
import {createInertiaApp,router} from '@inertiajs/vue3';
import CmsPage from '${root.replaceAll('\\', '/')}/resources/js/pages/public/CmsPage.vue';
import {i18n,syncLocale} from '${root.replaceAll('\\', '/')}/resources/js/i18n/index.ts';
import '${root.replaceAll('\\', '/')}/resources/css/app.css';
import '@fontsource-variable/inter';import '@fontsource-variable/sora';import '@fontsource-variable/noto-sans-arabic';
const query=new URLSearchParams(location.search),locale=query.get('locale')||'en';
const cms=${JSON.stringify(pages)}[query.has('known')?'known':'unknown'][locale];
if(query.has('invalid')){cms.sections.push({...cms.sections[0],id:'unknown',component_key:'toString'});cms.sections.push({...cms.sections[0],id:'invalid-variant',variant:'user-css'})}
window.fixture={cms,requests:[],reloads:0,failure:false};
document.cookie='XSRF-TOKEN=synthetic-fixture';
window.fetch=async(...args)=>{fixture.requests.push(args);return fixture.failure?{type:'basic',status:419}:{type:'opaqueredirect'}};
router.reload=(options)=>{fixture.reloads++;options.onFinish?.()};
syncLocale(locale);
await createInertiaApp({page:{component:'public/CmsPage',props:{cms,locale,direction:locale==='ar'?'rtl':'ltr',supportedLocales:['en','id','ar']},url:location.pathname,version:null},resolve:()=>CmsPage,setup:({el,App,props,plugin})=>createApp({render:()=>h(App,props)}).use(plugin).use(i18n).mount(el)});
window.fixture.ready=true;
`;
const report = { kind: 'SYNTHETIC UI FIXTURE — NO DATABASE', checks: [], result: 'RUNNING', limits: [
    'Real PHP validator/presenter projection and real Vue components; synthetic Event and snapshot only.',
    'Locale POST is intercepted. No authenticated session, Laravel HTTP persistence, migration or MySQL UAT.',
] };
let server, chrome, ws, send;
const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));
try {
    await build({ configFile: false, root, logLevel: 'warn', resolve: { alias: { '@': path.join(root, 'resources/js') } },
        plugins: [{ name: 'public-cms-fixture', resolveId: id => id === 'virtual:fixture' ? '\0fixture' : undefined, load: id => id === '\0fixture' ? source : undefined }, vue(), tailwind()],
        build: { outDir: path.join(temporary, 'dist'), emptyOutDir: true, target: 'esnext', rollupOptions: { input: 'virtual:fixture', output: { entryFileNames: 'fixture.js' } } } });
    const dist = path.join(temporary, 'dist');
    const styles = fs.readdirSync(path.join(dist, 'assets')).filter(file => file.endsWith('.css')).map(file => `<link rel="stylesheet" href="/assets/${file}">`).join('');
    server = http.createServer((request, response) => {
        const url = new URL(request.url, 'http://localhost');
        if (url.pathname === '/') {
            response.setHeader('Content-Type', 'text/html; charset=utf-8');
            response.end(`<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex, nofollow">${styles}</head><body><p style="padding:8px;background:#ffefb0;color:#111;font:12px monospace">SYNTHETIC UI FIXTURE — NO DATABASE</p><div id="app"></div><script type="module" src="/fixture.js"></script></body></html>`);
            return;
        }
        const file = path.resolve(dist, '.' + url.pathname);
        if (!file.startsWith(dist + path.sep) || !fs.existsSync(file)) { response.writeHead(404).end(); return; }
        response.setHeader('Content-Type', file.endsWith('.js') ? 'text/javascript' : file.endsWith('.css') ? 'text/css' : 'application/octet-stream');
        fs.createReadStream(file).pipe(response);
    });
    server.listen(0, '127.0.0.1'); await once(server, 'listening');
    const origin = `http://127.0.0.1:${server.address().port}`;
    const profile = path.join(temporary, 'profile');
    chrome = spawn('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe', ['--headless=new', '--remote-debugging-port=0', `--user-data-dir=${profile}`, '--no-first-run', '--no-default-browser-check', 'about:blank'], { windowsHide: true, stdio: 'ignore' });
    const portFile = path.join(profile, 'DevToolsActivePort');
    for (let i = 0; i < 100 && !fs.existsSync(portFile); i++) await sleep(100);
    const port = Number(fs.readFileSync(portFile, 'utf8').split('\n')[0]);
    const tabs = await (await fetch(`http://127.0.0.1:${port}/json`)).json();
    ws = new WebSocket(tabs.find(tab => tab.type === 'page').webSocketDebuggerUrl); await once(ws, 'open');
    let id = 0; const pending = new Map(), errors = [];
    ws.addEventListener('message', event => {
        const message = JSON.parse(event.data);
        if (!message.id) { if (message.method === 'Runtime.exceptionThrown') errors.push(message.params.exceptionDetails.text); return; }
        const callback = pending.get(message.id); pending.delete(message.id);
        message.error ? callback.reject(new Error(message.error.message)) : callback.resolve(message.result);
    });
    send = (method, params = {}) => new Promise((resolve, reject) => { pending.set(++id, { resolve, reject }); ws.send(JSON.stringify({ id, method, params })); });
    const evaluate = async expression => {
        const result = await send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
        if (result.exceptionDetails) throw new Error(result.exceptionDetails.exception?.description ?? result.exceptionDetails.text);
        return result.result.value;
    };
    const wait = async expression => { for (let i = 0; i < 100; i++) { if (await evaluate(expression)) return; await sleep(50); } throw new Error(`Timed out: ${expression}`); };
    const check = (value, label) => { assert.ok(value, label); report.checks.push(label); };
    const key = async (key, code, windowsVirtualKeyCode, text) => {
        await send('Input.dispatchKeyEvent', { type: 'keyDown', key, code, windowsVirtualKeyCode, ...(text ? { text, unmodifiedText: text } : {}) });
        await send('Input.dispatchKeyEvent', { type: 'keyUp', key, code, windowsVirtualKeyCode });
    };
    await send('Page.enable'); await send('Runtime.enable');
    await send('Page.bringToFront'); await send('Emulation.setFocusEmulationEnabled', { enabled: true });
    for (const locale of ['en', 'id', 'ar']) {
        for (const width of [375, 768, 1440]) {
            await send('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: width < 768 });
            await send('Page.navigate', { url: `${origin}/?locale=${locale}` }); await wait('window.fixture?.ready'); await evaluate('document.fonts.ready');
            check(await evaluate('document.documentElement.scrollWidth<=innerWidth+1'), `No overflow ${locale}/${width}`);
            check(await evaluate(`document.documentElement.dir===${JSON.stringify(locale === 'ar' ? 'rtl' : 'ltr')}`), `Direction ${locale}/${width}`);
            check(await evaluate("document.querySelectorAll('h1').length===1 && document.querySelectorAll('h2').length===4"), `Heading hierarchy ${locale}/${width}`);
            check(await evaluate("document.querySelectorAll('[data-section]').length===7"), `All renderers and variants ${locale}/${width}`);
            check(await evaluate("JSON.stringify([...document.querySelectorAll('[data-section]')].map(el=>[el.dataset.section,el.dataset.variant]))===JSON.stringify(fixture.cms.sections.map(s=>[s.component_key,s.variant]))"), `Saved order and repeated types ${locale}/${width}`);
            check(await evaluate("!document.body.textContent.includes('SECRET') && !document.querySelector('img,iframe,style[data-cms]')"), `No hidden/draft/private media ${locale}/${width}`);
            check(await evaluate("!document.querySelector('[data-section=countdown] time') && !/00 Days|00 Hours|00 Minutes|00 Seconds/.test(document.body.textContent)"), `Truthful missing time ${locale}/${width}`);
            check(await evaluate("document.querySelector('[data-section=cta] a').getAttribute('href')==='/information'"), `Published CTA ${locale}/${width}`);
            const fallback = { en: 'English content is shown', id: 'Konten bahasa Inggris ditampilkan', ar: 'يُعرض المحتوى باللغة الإنجليزية' }[locale];
            check(await evaluate(`${locale === 'en' ? '!' : ''}document.body.textContent.includes(${JSON.stringify(fallback)})`), `Fallback label ${locale}/${width}`);
            check(await evaluate(`document.querySelector('[data-section="editorial-copy"] [lang]').lang===${JSON.stringify(locale)}`), `Ready copy language ${locale}/${width}`);
            check(await evaluate("getComputedStyle(document.querySelector('[data-section=hero] [lang]')).direction==='ltr'"), `English fallback direction ${locale}/${width}`);
            check(await evaluate(`getComputedStyle(document.querySelector('[data-section=event-essentials] dt')).direction===${JSON.stringify(locale === 'ar' ? 'rtl' : 'ltr')}`), `Interface labels keep selected language direction ${locale}/${width}`);
            await key('Tab', 'Tab', 9);
            check(await evaluate("document.activeElement.getAttribute('href')==='#public-content' && document.activeElement.getBoundingClientRect().top>=0"), `Keyboard skip link ${locale}/${width}`);
            await key('Enter', 'Enter', 13, '\r');
            check(await evaluate("document.activeElement.id==='public-content'"), `Skip focuses main ${locale}/${width}`);
            await evaluate('document.activeElement.blur();scrollTo(0,0)');
            const { cssContentSize } = await send('Page.getLayoutMetrics');
            const screenshot = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: true,
                clip: { x: 0, y: 0, width, height: Math.ceil(cssContentSize.height), scale: 1 } });
            fs.writeFileSync(path.join(output, `${locale}-${width}.png`), Buffer.from(screenshot.data, 'base64'));
        }
    }
    await send('Page.navigate', { url: `${origin}/?locale=ar&known&invalid` }); await wait('window.fixture?.ready');
    check(await evaluate("document.querySelectorAll('[data-section]').length===7"), 'Unknown renderer/prototype key and unapproved variant fail closed');
    check(await evaluate("document.querySelector('[data-section=countdown] time').dateTime==='2027-01-05T09:15:00+07:00'"), 'Synthetic known time keeps explicit offset');
    await evaluate("document.querySelector('select').value='id';document.querySelector('select').dispatchEvent(new Event('change',{bubbles:true}))");
    await wait('fixture.reloads===1');
    check(await evaluate("fixture.requests[0][0]==='/locale' && JSON.parse(fixture.requests[0][1].body).locale==='id' && fixture.requests[0][1].redirect==='manual' && fixture.requests[0][1].headers['X-XSRF-TOKEN']==='synthetic-fixture'"), 'Language POST uses CSRF and reloads the public page without following auth redirect');
    await evaluate("fixture.failure=true;document.querySelector('select').value='en';document.querySelector('select').dispatchEvent(new Event('change',{bubbles:true}))");
    await wait("!!document.querySelector('[role=alert]')");
    check(await evaluate("fixture.reloads===1 && !document.querySelector('select').disabled && document.querySelector('select').value==='ar'"), 'Failed locale POST reports error and retains current language');
    check(errors.length === 0, 'No uncaught browser errors');
    report.result = 'PASS'; console.log(JSON.stringify({ result: report.result, checks: report.checks.length, kind: report.kind }));
} catch (error) { report.result = 'FAIL'; report.error = error.message; throw error; }
finally {
    fs.writeFileSync(path.join(output, 'results.json'), JSON.stringify(report, null, 2) + '\n');
    if (send && ws?.readyState === WebSocket.OPEN) await send('Browser.close').catch(() => {});
    ws?.close();
    if (server) await new Promise(resolve => server.close(resolve));
    for (let i = 0; i < 50 && chrome?.exitCode === null; i++) await sleep(100);
    if (chrome?.exitCode === null) { chrome.kill(); await sleep(500); }
    const resolvedTemp = fs.realpathSync(temporary);
    assert.ok(resolvedTemp.startsWith(fs.realpathSync(os.tmpdir()) + path.sep + 'iches-s104-ui-'));
    fs.rmSync(resolvedTemp, { recursive: true, force: true, maxRetries: 10, retryDelay: 200 });
}
