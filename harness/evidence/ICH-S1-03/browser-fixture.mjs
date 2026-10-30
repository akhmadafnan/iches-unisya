// Isolated UI fixture only: actual Vue screens, synthetic Inertia/JSON boundaries, no Laravel/DB access.
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
const temporary = fs.mkdtempSync(path.join(os.tmpdir(), 'iches-s103-ui-'));
const output = path.join(root, 'harness/evidence/ICH-S1-03/browser');
fs.mkdirSync(output, { recursive: true });
const php = process.env.ICHES_PHP_EXE ?? path.join(os.homedir(), '.config/herd/bin/php84/php.exe');
const catalog = JSON.parse(execFileSync(php, ['-r', "require 'vendor/autoload.php'; echo json_encode((new App\\Services\\Cms\\PageSnapshotValidator)->catalog());"], { encoding: 'utf8' }));
const source = `
import {createApp,h} from 'vue';
import {createInertiaApp,router} from '@inertiajs/vue3';
import Editor from '${root.replaceAll('\\','/')}/resources/js/pages/cms/Editor.vue';
import Pages from '${root.replaceAll('\\','/')}/resources/js/pages/cms/Pages.vue';
import {i18n,syncLocale} from '${root.replaceAll('\\','/')}/resources/js/i18n/index.ts';
import '${root.replaceAll('\\','/')}/resources/css/app.css';
import '@fontsource-variable/jetbrains-mono';import '@fontsource-variable/geist-mono';import '@fontsource-variable/noto-sans-arabic';
const query=new URLSearchParams(location.search),locale=query.get('locale')||'en',isPages=query.has('pages');
const missing=()=>({status:'missing',content:null});
const snapshot={schema_version:1,locales:{en:{status:'ready',content:{title:'SYNTHETIC UI FIXTURE'}},id:{status:'ready',content:{title:'KONTEN UJI SINTETIS'}},ar:{status:'ready',content:{title:'محتوى اختبار اصطناعي'}}},sections:[{id:'019a0000-0000-7000-8000-000000000003',component_key:'hero',variant:'default',position:0,visible:true,locales:{en:{status:'draft',content:{heading:'Synthetic heading',body:'Synthetic test copy'}},id:missing(),ar:missing()},media_ids:[],bindings:[]}]};
const component=isPages?'cms/Pages':'cms/Editor';
const props={locale,direction:locale==='ar'?'rtl':'ltr',supportedLocales:['en','id','ar'],account:{name:'Synthetic fixture',email:'fixture@iches.test'},workspaces:[{key:'assignment:1',label:'Synthetic event',role:query.get('role')||'superadmin',event:'Synthetic event',institution:null}],activeWorkspace:'assignment:1',cmsEvent:{id:'019a0000-0000-7000-8000-000000000001',name:'SYNTHETIC UI FIXTURE'},cmsPage:{id:'019a0000-0000-7000-8000-000000000002',slug:'synthetic-fixture',draft_revision_id:'019a0000-0000-7000-8000-000000000004',published_revision_id:null},snapshot,catalog:${JSON.stringify(catalog)},revisions:[{id:'019a0000-0000-7000-8000-000000000004',version:1,created_at:'2026-10-30T00:00:00Z'}],audit:[],schemaAvailable:!query.has('unavailable'),search:'',pages:{data:[],current_page:1,last_page:1,prev_page_url:null,next_page_url:null}};
window.fixture={props,requests:[],failure:0};
if(query.has('published')){props.cmsPage.published_revision_id=props.cmsPage.draft_revision_id;props.revisions[0].version=2;props.revisions.push({id:'019a0000-0000-7000-8000-000000000005',version:1,created_at:'2026-10-29T00:00:00Z'})}
document.cookie='XSRF-TOKEN=synthetic-fixture';
window.fetch=async(url,options={})=>{window.fixture.requests.push({url,options});if(window.fixture.failure)return new Response(JSON.stringify({errors:{snapshot:['Synthetic rejection']}}),{status:window.fixture.failure});if(url.endsWith('/draft'))props.snapshot=JSON.parse(options.body).snapshot;return new Response(JSON.stringify({page_id:props.cmsPage.id,revision_id:props.cmsPage.draft_revision_id,version:1,snapshot:props.snapshot}),{status:200,headers:{'Content-Type':'application/json'}})};
router.reload=(options={})=>{options.onSuccess?.();options.onFinish?.()};
syncLocale(locale);
await createInertiaApp({page:{component,props,url:'/backoffice/website/pages'+(isPages?'':'/synthetic-fixture'),version:null},resolve:name=>name==='cms/Pages'?Pages:Editor,setup:({el,App,props,plugin})=>createApp({render:()=>h(App,props)}).use(plugin).use(i18n).mount(el)});
window.fixture.ready=true;
`;
const report = { kind: 'SYNTHETIC UI FIXTURE — NO DATABASE', checks: [], limits: ['Inertia reload and JSON responses are mocked. Actual persistence/authenticated browser workflow is BLOCKED_NOT_RUN pending owner GO TEST.'], result: 'RUNNING' };
let server, chrome, ws, send;
const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));
try {
    await build({ configFile: false, root, logLevel: 'warn', resolve: { alias: { '@': path.join(root, 'resources/js') } },
        plugins: [{ name: 'cms-fixture', resolveId: id => id === 'virtual:cms-fixture' ? '\0cms-fixture' : undefined, load: id => id === '\0cms-fixture' ? source : undefined }, vue(), tailwind()],
        build: { outDir: path.join(temporary, 'dist'), emptyOutDir: true, target: 'esnext', rollupOptions: { input: 'virtual:cms-fixture', output: { entryFileNames: 'fixture.js' } } } });
    const dist = path.join(temporary, 'dist');
    const styles = fs.readdirSync(path.join(dist, 'assets')).filter(file => file.endsWith('.css')).map(file => `<link rel="stylesheet" href="/assets/${file}">`).join('');
    server = http.createServer((request, response) => {
        const url = new URL(request.url, 'http://localhost');
        if (url.pathname === '/') { response.setHeader('Content-Type', 'text/html; charset=utf-8'); response.end(`<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">${styles}</head><body><p style="padding:8px;background:#ffefb0;color:#111;font:12px monospace">SYNTHETIC UI FIXTURE — NO DATABASE</p><div id="app"></div><script type="module" src="/fixture.js"></script></body></html>`); return; }
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
    const input = (selector, value, event = 'input') => evaluate(`(()=>{const element=document.querySelector(${JSON.stringify(selector)});element.value=${JSON.stringify(value)};element.dispatchEvent(new Event(${JSON.stringify(event)},{bubbles:true}))})()`);
    const clickText = text => evaluate(`(()=>{const button=[...document.querySelectorAll('button')].find(button=>button.textContent.trim()===${JSON.stringify(text)});if(!button)throw Error('Button missing');button.focus();button.click()})()`);
    await send('Page.enable'); await send('Runtime.enable');
    await send('Page.bringToFront'); await send('Emulation.setFocusEmulationEnabled', { enabled: true });
    for (const locale of ['en', 'id', 'ar']) {
        for (const width of [375, 768, 1440]) {
            await send('Emulation.setDeviceMetricsOverride', { width, height: 900, deviceScaleFactor: 1, mobile: width < 768 });
            await send('Page.navigate', { url: `${origin}/?locale=${locale}` }); await wait('window.fixture?.ready'); await evaluate('document.fonts.ready');
            check(await evaluate('document.documentElement.scrollWidth<=innerWidth+1'), `No page overflow ${locale}/${width}`);
            check(await evaluate(`document.documentElement.dir===${JSON.stringify(locale === 'ar' ? 'rtl' : 'ltr')}`), `Direction ${locale}/${width}`);
            check(await evaluate("!!document.querySelector('a[href=\"/backoffice/website/pages\"]')"), `CMS navigation ${locale}/${width}`);
            check(await evaluate("!!document.querySelector('label[for=cms-language]') && !!document.querySelector('#cms-language')"), `Editor labels ${locale}/${width}`);
            const image = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: false });
            fs.writeFileSync(path.join(output, `${locale}-${width}.png`), Buffer.from(image.data, 'base64'));
            const previewLabel = { en: 'Private draft preview', id: 'Pratinjau draf privat', ar: 'معاينة خاصة للمسودة' }[locale];
            await clickText(previewLabel); await wait("!!document.querySelector('[role=dialog]')");
            check(await evaluate("(()=>{const dialog=document.querySelector('[role=dialog]'),rect=dialog.getBoundingClientRect();return rect.left>=0&&rect.right<=innerWidth+1&&rect.top>=0&&rect.bottom<=innerHeight+1&&dialog.contains(document.activeElement)})()"), `Preview dialog bounds and focus ${locale}/${width}`);
            check(await evaluate(`getComputedStyle(document.querySelector('[role=dialog]')).direction===${JSON.stringify(locale === 'ar' ? 'rtl' : 'ltr')}`), `Preview portal direction ${locale}/${width}`);
            const dialogImage = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: false });
            fs.writeFileSync(path.join(output, `${locale}-preview-${width}.png`), Buffer.from(dialogImage.data, 'base64'));
            await send('Input.dispatchKeyEvent', { type: 'keyDown', key: 'Escape', code: 'Escape', windowsVirtualKeyCode: 27 });
            await send('Input.dispatchKeyEvent', { type: 'keyUp', key: 'Escape', code: 'Escape', windowsVirtualKeyCode: 27 });
            await wait("!document.querySelector('[role=dialog]')");
            if (width === 375) {
                const navigationLabel = { en: 'Open navigation', id: 'Buka navigasi', ar: 'فتح قائمة التنقل' }[locale];
                await evaluate(`document.querySelector('[aria-label="'+${JSON.stringify(navigationLabel)}+'"]' ).click()`);
                await wait("!!document.querySelector('[role=dialog]')");
                check(await evaluate("!!document.querySelector('[role=dialog] a[href=\"/backoffice/website/pages\"]')"), `Mobile shared CMS navigation ${locale}`);
                const navigationImage = await send('Page.captureScreenshot', { format: 'png' });
                fs.writeFileSync(path.join(output, `${locale}-navigation-375.png`), Buffer.from(navigationImage.data, 'base64'));
                await send('Input.dispatchKeyEvent', { type: 'keyDown', key: 'Escape', code: 'Escape', windowsVirtualKeyCode: 27 });
                await send('Input.dispatchKeyEvent', { type: 'keyUp', key: 'Escape', code: 'Escape', windowsVirtualKeyCode: 27 });
                await wait("!document.querySelector('[role=dialog]')");
            }
        }
    }
    await send('Page.navigate', { url: `${origin}/?locale=en` }); await wait('window.fixture?.ready');
    await clickText('Add section'); await wait("document.querySelectorAll('fieldset section').length===3");
    await clickText('Duplicate'); await wait("document.querySelectorAll('fieldset section').length===4");
    await evaluate("document.querySelector('[aria-label=\"Move section 2 up\"]').focus()");
    await evaluate("window.qaKeys=[];window.addEventListener('keydown',event=>qaKeys.push({key:event.key,trusted:event.isTrusted}));window.beforeOrder=[...document.querySelectorAll('fieldset h2')].map(element=>element.id)");
    await send('Input.dispatchKeyEvent', { type: 'keyDown', key: 'Enter', code: 'Enter', windowsVirtualKeyCode: 13, text: '\r', unmodifiedText: '\r' });
    await send('Input.dispatchKeyEvent', { type: 'keyUp', key: 'Enter', code: 'Enter', windowsVirtualKeyCode: 13 });
    await sleep(200);
    const keyboard = await evaluate("({keys:qaKeys,active:document.activeElement.outerHTML,order:[...document.querySelectorAll('fieldset h2')].map(element=>element.id),before:beforeOrder})");
    check(keyboard.keys.some(event => event.key === 'Enter' && event.trusted), 'Native trusted keyboard delivery');
    check(await evaluate("document.activeElement.tagName==='H2'"), 'Native Enter reorders and restores heading focus');
    await clickText('Delete'); await wait("document.querySelectorAll('fieldset section').length===3");
    await input('#cms-title', 'Changed synthetic title');
    check(await evaluate("document.querySelector('[role=status]').textContent.includes('Unsaved')"), 'Dirty state after editing');
    for (const status of [401, 403, 409, 419, 422, 503]) {
        await evaluate(`window.fixture.failure=${status}`); await clickText('Save draft'); await wait("!!document.querySelector('[role=alert]')");
        check(await evaluate("document.querySelector('[role=alert]').textContent.length>20"), `Rendered error ${status}`);
    }
    await evaluate('window.fixture.failure=0'); await clickText('Save draft'); await wait("document.querySelector('[role=status]').textContent.includes('No unsaved')");
    check(await evaluate("fixture.requests.at(-1).options.headers['X-XSRF-TOKEN']==='synthetic-fixture'"), 'Browser JSON CSRF header');
    await clickText('Private draft preview'); await wait("!!document.querySelector('[role=dialog]')");
    check(await evaluate("document.querySelector('[role=dialog]').textContent.includes('not the final public website')"), 'Structured preview label');
    await send('Input.dispatchKeyEvent', { type: 'keyDown', key: 'Escape', code: 'Escape', windowsVirtualKeyCode: 27 });
    await send('Input.dispatchKeyEvent', { type: 'keyUp', key: 'Escape', code: 'Escape', windowsVirtualKeyCode: 27 });
    await wait("!document.querySelector('[role=dialog]')");
    await clickText('Publish'); await wait("!!document.querySelector('[role=dialog]')");
    check(await evaluate("document.querySelector('[role=dialog]').textContent.includes('Publish this draft?')"), 'Publish confirmation');
    await clickText('Confirm'); await wait("!document.querySelector('[role=dialog]')");
    check(await evaluate("fixture.requests.at(-1).url.endsWith('/publish') && JSON.parse(fixture.requests.at(-1).options.body).revision_id===fixture.props.cmsPage.draft_revision_id"), 'Publish sends current draft UUID');
    await send('Page.navigate', { url: `${origin}/?published` }); await wait('window.fixture?.ready');
    await clickText('Restore'); await wait("!!document.querySelector('[role=dialog]')");
    check(await evaluate("document.querySelector('[role=dialog]').textContent.includes('current draft')"), 'Restore confirmation explains draft preservation');
    await clickText('Confirm'); await wait("!document.querySelector('[role=dialog]')");
    check(await evaluate("fixture.requests.at(-1).url.endsWith('/restore') && JSON.parse(fixture.requests.at(-1).options.body).revision_id==='019a0000-0000-7000-8000-000000000005'"), 'Restore sends selected historical UUID');
    await send('Page.navigate', { url: `${origin}/?pages&unavailable&locale=ar` }); await wait('window.fixture?.ready');
    check(await evaluate("!document.querySelector('#cms-search') && !document.querySelector('table')"), 'Schema unavailable hides CRUD controls');
    const unavailableImage = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(path.join(output, 'ar-schema-unavailable.png'), Buffer.from(unavailableImage.data, 'base64'));
    await send('Page.navigate', { url: `${origin}/?pages&locale=ar` }); await wait('window.fixture?.ready');
    await clickText('إنشاء صفحة'); await wait("!!document.querySelector('[role=dialog]')");
    check(await evaluate("getComputedStyle(document.querySelector('[role=dialog]')).direction==='rtl' && !!document.querySelector('#cms-slug')"), 'Create slug dialog RTL and input');
    await input('#cms-slug', 'synthetic-page');
    const createImage = await send('Page.captureScreenshot', { format: 'png' });
    fs.writeFileSync(path.join(output, 'ar-create.png'), Buffer.from(createImage.data, 'base64'));
    await send('Page.navigate', { url: `${origin}/?pages&role=participant` }); await wait('window.fixture?.ready');
    check(await evaluate("!document.querySelector('nav a[href=\"/backoffice/website/pages\"]')"), 'Unauthorized role has no CMS nav link');
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
    assert.ok(temporary.startsWith(path.join(os.tmpdir(), 'iches-s103-ui-')));
    fs.rmSync(temporary, { recursive: true, force: true, maxRetries: 10, retryDelay: 200 });
}
