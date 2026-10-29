// Local theme isolation and existing lifecycle regression via Chrome DevTools.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');
const { spawn } = require('node:child_process');
const { once } = require('node:events');

const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));
const origin = process.env.ICHES_QA_ORIGIN || 'http://iches-unisya.test';
const labels = { en: 'Apply', id: 'Terapkan', ar: 'تطبيق' };
const greetings = { en: 'Welcome,', id: 'Selamat datang,', ar: 'مرحباً،' };

async function main() {
    assert.equal(process.env.ICHES_BROWSER_QA, '1', 'Explicit local browser opt-in required.');
    assert.ok(process.env.ICHES_QA_DEMO_PASSWORD, 'Supply the existing synthetic demo password through the process environment.');
    assert.equal(os.hostname().toUpperCase(), 'DESKTOP-PE5D86C');
    assert.equal(fs.realpathSync('.').toLowerCase(), 'd:\\pinjam-afnan\\herd\\iches-unisya');
    const profile = fs.mkdtempSync(path.join(os.tmpdir(), 'iches-ui01b-'));
    const output = path.resolve(process.env.ICHES_QA_OUTPUT || 'harness/evidence/ICH-UI-01B/browser');
    fs.mkdirSync(output, { recursive: true });
    const chrome = spawn('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe', [
        '--headless=new', '--remote-debugging-port=0', `--user-data-dir=${profile}`,
        '--no-first-run', '--no-default-browser-check', 'about:blank',
    ], { windowsHide: true, stdio: 'ignore' });
    let ws;
    let send;
    let checks = 0;
    const report = { checks: 0, cases: [], network: [] };
    const check = (value, expected, message) => { assert.deepEqual(value, expected, message); checks++; };
    const canonical = value => value.startsWith('oklch(')
        ? value.match(/[\d.]+%?/g).map(n=>Number((n.endsWith('%')?Number(n.slice(0,-1))/100:Number(n)).toFixed(9)))
        : value.endsWith('rem') ? Number.parseFloat(value) : value.toLowerCase();
    try {
        const portFile = path.join(profile, 'DevToolsActivePort');
        for (let i = 0; i < 100 && !fs.existsSync(portFile); i++) await sleep(100);
        const port = Number(fs.readFileSync(portFile, 'utf8').split('\n')[0]);
        const tabs = await (await fetch(`http://127.0.0.1:${port}/json`)).json();
        ws = new WebSocket(tabs.find(tab => tab.type === 'page').webSocketDebuggerUrl);
        await once(ws, 'open');
        let sequence = 0;
        const pending = new Map();
        const events = [];
        ws.addEventListener('message', event => {
            const message = JSON.parse(event.data);
            if (!message.id) { events.push(message); return; }
            const request = pending.get(message.id);
            pending.delete(message.id);
            if (message.error) request.reject(new Error(message.error.message));
            else request.resolve(message.result);
        });
        send = (method, params = {}) => new Promise((resolve, reject) => {
            const id = ++sequence;
            pending.set(id, { resolve, reject });
            ws.send(JSON.stringify({ id, method, params }));
        });
        const evaluate = async expression => {
            const result = await send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
            assert.ok(!result.exceptionDetails, 'Browser evaluation must succeed.');
            return result.result.value;
        };
        const wait = async predicate => {
            for (let i = 0; i < 100; i++) {
                if (await predicate()) return;
                await sleep(50);
            }
            report.timeout = {predicate:String(predicate),state:await state(),alerts:await evaluate("[...document.querySelectorAll('[role=alert]')].map(e=>e.textContent)"),responses:events.filter(e=>e.method==='Network.responseReceived').slice(-5).map(e=>({status:e.params.response.status,path:new URL(e.params.response.url).pathname}))};
            throw new Error('Browser condition timed out.');
        };
        const state = () => evaluate(`({path:location.pathname,lang:document.documentElement.lang,dir:document.documentElement.dir,
            heading:document.querySelector('main h1')?.textContent.trim(),width:innerWidth,scroll:document.documentElement.scrollWidth,
            apply:document.querySelector('form:has(select) button')?.textContent.trim(),sentinel:window.qaSentinel})`);
        const router = `document.querySelector('#app').__vue_app__.config.globalProperties.$inertia`;
        const click = selector => evaluate(`(()=>{const el=document.querySelector(${JSON.stringify(selector)});el.focus();el.click()})()`);
        const screenshot = async name => {
            const image = await send('Page.captureScreenshot', { format: 'png' });
            fs.writeFileSync(path.join(output, name.endsWith('.png')?name:name+'.png'), Buffer.from(image.data, 'base64'));
        };
        const snapshot = async name => {
            await evaluate('document.fonts.ready');
            const styles = await evaluate(`Object.fromEntries(['body','main','h1','input','button'].map(selector=>{
                const element=document.querySelector(selector);if(!element)return [selector,null];
                const s=getComputedStyle(element);return [selector,Object.fromEntries(['backgroundColor','color','fontFamily','fontSize','borderRadius','borderColor'].map(p=>[p,s[p]]))];
            }))`);
            report.styles ??= {}; report.styles[name]=styles;
            await screenshot(name+'.png');
        };
        const theme = async admin => {
            const actual = await evaluate(`(()=>{const s=getComputedStyle(document.body);return {
                admin:!!document.querySelector('.iches-backoffice'),
                tokens:Object.fromEntries(${JSON.stringify(Object.keys(require('./registry-reference.json').tokens))}.map(k=>[k,s.getPropertyValue('--'+k).trim()])),
                font:s.fontFamily,background:s.backgroundColor}})()`);
            check(actual.admin, admin, 'Runtime Admin marker follows Inertia page');
            if (admin) {
                check(Object.fromEntries(Object.entries(actual.tokens).map(([k,v])=>[k,canonical(v)])),
                    Object.fromEntries(Object.entries(require('./registry-reference.json').tokens).map(([k,v])=>[k,canonical(v)])),
                    'Every official Neutral/Emerald light token including radius');
                check(actual.font.includes('JetBrains Mono Variable'), true, 'Admin body font');
            } else {
                check(actual.tokens.background.toLowerCase(), '#f7f6f1', 'Public ivory restored');
                check(actual.tokens.primary.toLowerCase(), '#164734', 'Public forest restored');
                check(actual.tokens.radius, '', 'Admin radius removed');
                check(actual.font.includes('JetBrains'), false, 'Admin font removed');
            }
            report.themes ??= []; report.themes.push(actual);
        };
        const platformFonts = async selector => {
            const doc = await send('DOM.getDocument');
            const {nodeId} = await send('DOM.querySelector', {nodeId:doc.root.nodeId,selector});
            const {fonts} = await send('CSS.getPlatformFontsForNode', {nodeId});
            return fonts.filter(f=>f.glyphCount>0).map(f=>f.familyName);
        };
        const adminMatrix = async locale => {
            await theme(true);
            await evaluate('document.fonts.ready');
            const bodyFonts=await platformFonts('main p');
            const headingFonts=await platformFonts('main h1');
            check(bodyFonts.some(f=>f.includes('JetBrains')), true, 'Rendered Admin body uses JetBrains');
            check(headingFonts.some(f=>f.includes(locale==='ar'?'Noto Sans Arabic':'Geist Mono')), true, 'Rendered heading font / Arabic fallback');
            report.fonts ??= []; report.fonts.push({locale,bodyFonts,headingFonts});
            for (const width of [390,768,1440]) {
                await send('Emulation.setDeviceMetricsOverride',{width,height:900,mobile:width===390,deviceScaleFactor:1});
                await sleep(100);
                check(await evaluate('document.documentElement.scrollWidth<=innerWidth'),true,'No overflow: '+locale+'/'+width);
                await screenshot(locale+'-'+width);
            }
            await click('button[aria-haspopup=menu]');
            await wait(()=>evaluate("!!document.querySelector('[role=menu]')"));
            const portal=await evaluate(`(()=>{const el=document.querySelector('[role=menu]');const s=getComputedStyle(el);return {outside:!el.closest('.iches-backoffice'),background:s.backgroundColor,font:s.fontFamily,primary:s.getPropertyValue('--primary').trim(),dir:s.direction}})()`);
            check(portal.outside,true,'Dropdown is a real body portal');
            check(portal.background,'oklch(1 0 0)','Portal card color');
            check(canonical(portal.primary),canonical(require('./registry-reference.json').tokens.primary),'Portal Emerald context');
            check(portal.font.includes('JetBrains'),true,'Portal font');
            check(portal.dir,locale==='ar'?'rtl':'ltr','Dropdown direction');
            report.portals ??= []; report.portals.push({locale,...portal});
            await screenshot(locale+'-dropdown');
            await evaluate("document.querySelector('[role=menu]').focus()");
            // CDP key injection is unavailable in this desktop session; dispatch in the page.
            await evaluate("window.dispatchEvent(new KeyboardEvent('keydown',{key:'Escape',code:'Escape',bubbles:true,cancelable:true}))");
            await wait(()=>evaluate("!document.querySelector('[role=menu]')"));
            check(await evaluate("document.activeElement===document.querySelector('button[aria-haspopup=menu]')"),true,'Dropdown focus restored');
            await send('Emulation.setDeviceMetricsOverride',{width:390,height:844,mobile:true,deviceScaleFactor:1});
            await click('button[aria-haspopup=dialog]');
            await wait(()=>evaluate("!!document.querySelector('[role=dialog][data-state=open]')"));
            const sheet=await evaluate(`(()=>{const e=document.querySelector('[role=dialog]'),s=getComputedStyle(e),r=e.getBoundingClientRect();return {outside:!e.closest('.iches-backoffice'),font:s.fontFamily,primary:s.getPropertyValue('--primary').trim(),dir:s.direction,left:Math.round(r.left),right:Math.round(r.right),focus:!!document.activeElement.closest('[role=dialog]')}})()`);
            check(sheet.outside,true,'Sheet is a real body portal');
            check(canonical(sheet.primary),canonical(require('./registry-reference.json').tokens.primary),'Sheet Emerald context');
            check(sheet.font.includes('JetBrains'),true,'Sheet font');
            check(sheet.dir,locale==='ar'?'rtl':'ltr','Sheet direction');
            check(locale==='ar'?sheet.right:sheet.left,locale==='ar'?390:0,'Sheet logical edge');
            check(sheet.focus,true,'Sheet initial focus');
            report.portals.push({locale,type:'sheet',...sheet});
            await screenshot(locale+'-sheet');
            await click('[role=dialog] button');
            await wait(()=>evaluate("!document.querySelector('[role=dialog][data-state=open]')"));
            await sleep(100);
            check(await evaluate("document.activeElement===document.querySelector('button[aria-haspopup=dialog]')"),true,'Sheet trigger focus restored');
        };
        const change = async (locale, dashboard = true) => {
            const start = events.length;
            await evaluate(`window.qaFinished=false;window.qaEvents=[];window.qaSentinel='same-document';
                document.addEventListener('inertia:finish',()=>{window.qaFinished=true},{once:true});
                document.querySelector('select').value=${JSON.stringify(locale)};
                document.querySelector('select').dispatchEvent(new Event('change',{bubbles:true}));`);
            const began = Date.now();
            await click('form:has(select) button');
            await wait(() => evaluate('window.qaFinished'));
            const current = await state();
            check(current.lang, locale, 'Same-URL success must synchronize html lang.');
            check(current.dir, locale === 'ar' ? 'rtl' : 'ltr', 'Same-URL direction');
            check(current.apply, labels[locale], 'Shell translation');
            check(current.sentinel, 'same-document', 'Locale switch must not reload');
            check(current.path, dashboard ? '/dashboard' : '/login', 'No unexpected navigation');
            if (dashboard) check(current.heading.startsWith(greetings[locale]), true, 'Dashboard translation');
            const trace = events.slice(start);
            const posts = trace.filter(e => e.method === 'Network.requestWillBeSent' && e.params.request.method === 'POST' && new URL(e.params.request.url).pathname === '/locale');
            const redirects = trace.filter(e => e.method === 'Network.requestWillBeSent' && e.params.redirectResponse);
            const responses = trace.filter(e => e.method === 'Network.responseReceived' && new URL(e.params.response.url).pathname === current.path);
            check(posts.length, 1, 'Exactly one locale POST');
            check(redirects.map(e => e.params.redirectResponse.status), [302], 'One expected redirect');
            check(responses.map(e => e.params.response.status), [200], 'One successful page response');
            check(trace.filter(e => e.method === 'Network.requestWillBeSent' && e.params.type === 'Document').length, 0, 'No document reload');
            report.network.push({ locale, page: current.path, elapsedMs: Date.now() - began, statuses: [302, 200] });
            report.cases.push(current);
        };
        await send('Page.enable'); await send('DOM.enable'); await send('CSS.enable'); await send('Runtime.enable'); await send('Network.enable');
        await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, mobile: true, deviceScaleFactor: 1 });
        await send('Page.navigate', { url: `${origin}/dev/login` });
        await wait(() => evaluate(`!!document.querySelector('input[type=password]')`));
        await snapshot('auth-before-login');
        await send('Page.navigate', {url:origin+'/'});
        await wait(()=>evaluate("!!document.querySelector('#foundation-title')"));
        await snapshot('public');
        for(const width of [390,768,1440]) {
            await send('Emulation.setDeviceMetricsOverride',{width,height:900,mobile:width===390,deviceScaleFactor:1});
            check(await evaluate('document.documentElement.scrollWidth<=innerWidth'),true,'Public no overflow: '+width);
            await screenshot('public-'+width);
        }
        await send('Emulation.setDeviceMetricsOverride',{width:390,height:844,mobile:true,deviceScaleFactor:1});
        await send('Page.navigate', {url:origin+'/dev/login'});
        await wait(()=>evaluate("!!document.querySelector('input[type=password]')"));
        await evaluate(`for(const [type,value] of [['email','presenter@iches.test'],['password',${JSON.stringify(process.env.ICHES_QA_DEMO_PASSWORD)}]]){
            const input=document.querySelector('input[type='+type+']');input.value=value;input.dispatchEvent(new Event('input',{bubbles:true}));}`);
        await click('form:has(input[type=email]) button');
        await wait(async () => (await state()).path === '/dashboard');
        check((await state()).lang, 'en', 'Fresh demo session locale');
        for (const locale of ['id', 'ar', 'en']) { await change(locale); await adminMatrix(locale); }
        await change('ar');
        await screenshot('ar-390-no-reload.png');
        await send('Page.reload');
        await wait(async () => (await state()).lang === 'ar' && !!(await state()).heading);
        check((await state()).dir, 'rtl', 'Session persists across reload');
        for (const width of [390, 768, 1440]) {
            await send('Emulation.setDeviceMetricsOverride', { width, height: 900, mobile: width === 390, deviceScaleFactor: 1 });
            await sleep(100);
            const current = await state();
            check(current.width, width, 'Actual emulated viewport');
            check(current.scroll <= width, true, 'No horizontal overflow');
            report.cases.push(current);
            await screenshot(`ar-${width}.png`);
        }
        await send('Emulation.setDeviceMetricsOverride', { width: 390, height: 844, mobile: true, deviceScaleFactor: 1 });
        await click('button[aria-haspopup=dialog]');
        await wait(() => evaluate(`!!document.querySelector('[role=dialog][data-state=open]')`));
        check(await evaluate(`!!document.activeElement.closest('[role=dialog]')`), true, 'Drawer traps initial focus');
        check(canonical(await evaluate(`getComputedStyle(document.querySelector('[role=dialog]')).getPropertyValue('--primary').trim()`)), canonical(require('./registry-reference.json').tokens.primary), 'Sheet portal theme');
        check(await evaluate(`getComputedStyle(document.querySelector('[role=dialog]')).fontFamily.includes('JetBrains')`), true, 'Sheet portal font');
        check(await evaluate(`getComputedStyle(document.querySelector('[role=dialog]')).direction`), 'rtl', 'Sheet portal RTL');
        check(await evaluate(`Math.round(document.querySelector('[role=dialog]').getBoundingClientRect().right)`), 390, 'RTL drawer at right');
        await screenshot('ar-drawer.png');
        await click('[role=dialog] button');
        await wait(() => evaluate(`!document.querySelector('[role=dialog][data-state=open]')`));
        check(await evaluate(`document.activeElement===document.querySelector('button[aria-haspopup=dialog]')`), true, 'Close restores trigger focus');
        await evaluate(`${router}.visit('/')`);
        await wait(async () => (await state()).path === '/');
        await theme(false);
        await evaluate('history.back()');
        await wait(async () => (await state()).path === '/dashboard');
        await theme(true);
        check((await state()).lang, 'ar', 'Inertia history back locale');
        await change('en');
        await evaluate('history.forward()');
        await wait(async () => (await state()).path === '/');
        check((await state()).lang, 'ar', 'Inertia history forward locale');
        await evaluate('history.back()');
        await wait(async () => (await state()).path === '/dashboard');
        check((await state()).lang, 'en', 'History restores a different saved locale');
        const workspace = await evaluate(`(()=>{const s=[...document.querySelectorAll('select')].find(x=>[...x.options].some(o=>o.value.startsWith('assignment:')));return s?.querySelector('option[value^="assignment:"]')?.value})()`);
        assert.ok(workspace, 'Existing demo workspace grant required.');
        await evaluate(`(()=>{const s=[...document.querySelectorAll('select')].find(x=>[...x.options].some(o=>o.value===${JSON.stringify(workspace)}));s.value=${JSON.stringify(workspace)};s.dispatchEvent(new Event('change',{bubbles:true}));})()`);
        await evaluate(`window.qaWorkspaceFinished=false;document.addEventListener('inertia:finish',()=>{window.qaWorkspaceFinished=true},{once:true})`);
        await click('form:has(option[value^="assignment:"]) button');
        await wait(() => evaluate('window.qaWorkspaceFinished'));
        check(await evaluate(`fetch('/me/workspaces').then(r=>r.json()).then(data=>data.activeWorkspace===${JSON.stringify(workspace)})`), true, 'Workspace selection persisted on server');
        check(await evaluate(`document.body.textContent.includes('presenter@iches.test')`), true, 'Workspace keeps actor identity');
        await evaluate(`${router}.post('/logout')`);
        await wait(async () => (await state()).path === '/login');
        for (const locale of ['id', 'ar', 'en']) {
            await change(locale, false); await theme(false); await snapshot('auth-'+locale);
            for (const width of [390,768,1440]) {
                await send('Emulation.setDeviceMetricsOverride',{width,height:900,mobile:width===390,deviceScaleFactor:1});
                check(await evaluate('document.documentElement.scrollWidth<=innerWidth'),true,'Auth no overflow: '+locale+'/'+width);
                await screenshot('auth-'+locale+'-'+width);
            }
            await send('Emulation.setDeviceMetricsOverride',{width:390,height:844,mobile:true,deviceScaleFactor:1});
        }
        await send('Page.navigate', { url: `${origin}/dashboard` });
        await wait(async () => (await state()).path === '/login');
        check((await state()).lang, 'en', 'Logout/session protection preserved');
        check(events.filter(e => e.method === 'Runtime.exceptionThrown').length, 0, 'No uncaught browser exceptions');
        report.checks = checks;
        report.result = 'PASS';
        console.log(JSON.stringify({ result: 'PASS', checks, output }));
    } catch (error) {
        report.result = 'FAIL'; report.checks = checks; report.error = error.message;
        throw error;
    } finally {
        fs.writeFileSync(path.join(output, 'browser-results.json'), JSON.stringify(report, null, 2));
        if (send && ws?.readyState === WebSocket.OPEN) await send('Browser.close').catch(() => {});
        ws?.close();
        for (let i = 0; i < 40 && chrome.exitCode === null; i++) await sleep(100);
        if (chrome.exitCode === null) chrome.kill();
        // Delete only the uniquely created Chrome profile beneath the OS temp directory.
        assert.ok(path.resolve(profile).startsWith(path.join(path.resolve(os.tmpdir()), 'iches-ui01b-')));
        fs.rmSync(profile, { recursive: true, force: true, maxRetries: 10, retryDelay: 100 });
    }
}

main().catch(error => { console.error(error.message); process.exitCode = 1; });
