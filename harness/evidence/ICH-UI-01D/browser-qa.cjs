// Local UI regression using native CDP input. No packages or server changes.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');
const { spawn } = require('node:child_process');
const { once } = require('node:events');
const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));
const output = __dirname;
const origin = 'http://iches-unisya.test';

async function main() {
    assert.equal(os.hostname(), 'DESKTOP-PE5D86C');
    const profile = process.env.ICHES_QA_PROFILE || fs.mkdtempSync(path.join(os.tmpdir(), 'iches-ui01d-'));
    assert.ok(path.resolve(profile).startsWith(path.join(path.resolve(os.tmpdir()), 'iches-ui01d-')));
    if (process.env.ICHES_QA_PROFILE) fs.rmSync(path.join(profile, 'DevToolsActivePort'), { force: true });
    const chrome = spawn('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe', [
        '--headless=new', '--remote-debugging-port=0', `--user-data-dir=${profile}`,
        '--no-first-run', '--no-default-browser-check', 'about:blank',
    ], { windowsHide: true, stdio: 'ignore' });
    const report = { result: 'RUNNING', checks: [], measurements: [], requests: [],
        input: 'Native CDP mouse, keyboard, and text; locale/workspace selects use synthetic change events.',
        limitations: ['No screen-reader UAT.', 'Valid OTP/Magic redemption not exercised; browser checks request success and invalid redemption.'] };
    let ws, send;
    try {
        const portFile = path.join(profile, 'DevToolsActivePort');
        for (let i = 0; i < 100 && !fs.existsSync(portFile); i++) await sleep(100);
        const port = Number(fs.readFileSync(portFile, 'utf8').split('\n')[0]);
        const tabs = await (await fetch(`http://127.0.0.1:${port}/json`)).json();
        ws = new WebSocket(tabs.find(tab => tab.type === 'page').webSocketDebuggerUrl);
        await once(ws, 'open');
        let sequence = 0;
        const pending = new Map();
        const exceptions = [];
        ws.addEventListener('message', event => {
            const message = JSON.parse(event.data);
            if (!message.id) {
                if (message.method === 'Runtime.exceptionThrown') exceptions.push(message.params.exceptionDetails.text);
                if (message.method === 'Network.requestWillBeSent') {
                    const r = message.params.request, u = new URL(r.url);
                    if (u.origin === origin && r.method === 'POST') report.requests.push({ path: u.pathname, method: r.method });
                }
                return;
            }
            const p = pending.get(message.id); pending.delete(message.id);
            message.error ? p.reject(new Error(message.error.message)) : p.resolve(message.result);
        });
        send = (method, params = {}) => new Promise((resolve, reject) => {
            const id = ++sequence; pending.set(id, { resolve, reject }); ws.send(JSON.stringify({ id, method, params }));
        });
        const evaluate = async expression => {
            const r = await send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
            assert.ok(!r.exceptionDetails, `Browser expression: ${r.exceptionDetails?.exception?.description || r.exceptionDetails?.text || ''}`); return r.result.value;
        };
        const wait = async expression => {
            for (let i = 0; i < 160; i++) { if (await evaluate(expression)) return; await sleep(50); }
            throw new Error(`Timeout: ${expression}`);
        };
        const check = (value, label) => { assert.ok(value, label); report.checks.push(label); };
        const key = async (key, code = key, modifiers = 0) => {
            await send('Input.dispatchKeyEvent', { type: 'keyDown', key, code, modifiers, windowsVirtualKeyCode: { a: 65, Tab: 9, Enter: 13, Escape: 27, ArrowDown: 40 }[key], ...(key === 'Enter' ? { text: '\r', unmodifiedText: '\r' } : {}) });
            await send('Input.dispatchKeyEvent', { type: 'keyUp', key, code, modifiers });
        };
        const click = async selector => {
            await evaluate(`document.querySelector(${JSON.stringify(selector)}).scrollIntoView({block:'center',behavior:'instant'})`);
            await sleep(60);
            const rect = await evaluate(`(()=>{const r=document.querySelector(${JSON.stringify(selector)}).getBoundingClientRect();return {x:r.x+r.width/2,y:r.y+r.height/2}})()`);
            await send('Input.dispatchMouseEvent', { type: 'mousePressed', ...rect, button: 'left', clickCount: 1 });
            await send('Input.dispatchMouseEvent', { type: 'mouseReleased', ...rect, button: 'left', clickCount: 1 });
            await sleep(60);
        };
        const fill = async (selector, text) => { await click(selector); await key('a', 'KeyA', 2); await send('Input.insertText', { text }); };
        const viewport = async (width, height = 900) => { await send('Emulation.setDeviceMetricsOverride', { width, height, mobile: width === 390, deviceScaleFactor: 1 }); await sleep(120); };
        const navigate = async (url, ready) => { await send('Page.navigate', { url: origin + url }); await wait(ready); };
        const screenshot = async name => { const r = await send('Page.captureScreenshot', { format: 'png', captureBeyondViewport: false }); fs.writeFileSync(path.join(output, name + '.png'), Buffer.from(r.data, 'base64')); };
        const locale = async value => {
            await evaluate(`(()=>{window.qaFinished=false;document.addEventListener('inertia:finish',()=>window.qaFinished=true,{once:true});const s=document.querySelector('form:has(select) select');s.value=${JSON.stringify(value)};s.dispatchEvent(new Event('change',{bubbles:true}));})()`);
            await click('form:has(select) button'); await wait(`window.qaFinished && document.documentElement.lang===${JSON.stringify(value)}`);
            check(await evaluate(`document.documentElement.dir===${JSON.stringify(value === 'ar' ? 'rtl' : 'ltr')}`), `Locale ${value} direction`);
        };
        const measure = async (kind, lang, width, height = 900) => {
            const m = await evaluate(`(()=>{const main=document.querySelector('main'), section=main.querySelector('section'), h=main.querySelector('h1'), sidebar=document.querySelector('aside');const r=section.getBoundingClientRect();return {kind:${JSON.stringify(kind)},lang:${JSON.stringify(lang)},width:innerWidth,height:innerHeight,scroll:document.documentElement.scrollWidth,section:{x:r.x,y:r.y,width:r.width,height:r.height},bodyFont:getComputedStyle(document.body).fontFamily,headingFont:getComputedStyle(h).fontFamily,sidebar:sidebar?getComputedStyle(sidebar).backgroundColor:null,mainPadding:getComputedStyle(main).paddingInlineStart,headerPadding:document.querySelector('header')?getComputedStyle(document.querySelector('header')).paddingInlineStart:null}})()`);
            check(m.width === width && m.scroll <= width, `${kind} ${lang} ${width}x${height}: no horizontal overflow`);
            check(m.bodyFont.includes(kind === 'dashboard' ? 'JetBrains' : 'Inter') && m.headingFont.includes(kind === 'dashboard' ? 'Geist' : 'Sora') && m.bodyFont.includes('Noto Sans Arabic'), `${kind} ${lang}: scoped fonts`);
            if (kind === 'dashboard') check(m.mainPadding === m.headerPadding, `Dashboard ${lang} ${width}: header padding alignment`);
            else {
                check(m.section.width <= 384 && Math.abs(m.section.x + m.section.width / 2 - width / 2) < 2, `Auth ${lang} ${width}: compact horizontally centered`);
                if (m.section.height + 64 <= height) check(Math.abs(m.section.y + m.section.height / 2 - height / 2) < 2, `Auth ${lang}: vertically centered`);
                else check(m.section.y >= 0, `Auth ${lang}: short viewport begins in scrollable area`);
            }
            report.measurements.push(m); await screenshot(`${kind}-${lang}-${width}x${height}`);
        };
        await send('Page.enable'); await send('Runtime.enable'); await send('Network.enable');
        await viewport(390);
        if (!process.env.ICHES_QA_DASHBOARD_ONLY) {
        await navigate('/login', `!!document.querySelector('#email')`);
        for (const lang of ['en', 'id', 'ar']) {
            if (lang !== 'en') await locale(lang);
            for (const width of [390, 768, 1440]) { await viewport(width); await measure('login', lang, width); }
            await viewport(390, 360); await measure('login', lang, 390, 360);
            await click('form:has(#email) button[type=button]'); await wait(`!!document.querySelector('#code')`);
            check(await evaluate(`!document.querySelector('#email') && document.activeElement.id==='verify-email'`), `OTP ${lang}: direct Verify and focus`);
            await screenshot(`verify-${lang}-short`);
            await click('form:has(#code) button[type=button]'); await wait(`!!document.querySelector('#email')`);
            check(await evaluate(`document.activeElement.id==='email'`), `OTP ${lang}: Request/back focus`);
        }
        await locale('en'); await viewport(390, 844);
        await fill('#email', 'presenter@iches.test');
        await click('form:has(#email) button:not([type=button])');
        await wait(`!!document.querySelector('#code') || !!document.querySelector('[role=alert]')`);
        if (await evaluate(`!!document.querySelector('#email')`)) {
            check(await evaluate(`!!document.querySelector('#email[aria-invalid=true]') && !document.querySelector('form:has(#email) button').disabled`), 'Request throttle is readable and retry is enabled');
            report.limitations.push('Request endpoint reached its existing 15-minute rate limit during repeated QA. Earlier attempts exercised successful request/resend; this run verifies the throttled state.');
            await click('form:has(#email) button[type=button]'); await wait(`!!document.querySelector('#code')`);
        } else {
            check(await evaluate(`document.querySelector('#verify-email').value==='presenter@iches.test' && document.activeElement.id==='code'`), 'OTP request succeeds, copies email and focuses code');
        }
        await fill('#code', '000000');
        check(await evaluate(`document.querySelector('#code').value.length===6 && document.querySelector('form:has(#code)').checkValidity()`), 'OTP Verify form valid before native submit');
        await click('form:has(#code) button:not([type=button])');
        await wait(`!!document.querySelector('[role=alert]')`);
        check(await evaluate(`!!document.querySelector('#code') && document.querySelector('#code').getAttribute('aria-invalid')==='true' && !document.querySelector('form:has(#code) button').disabled`), 'OTP invalid response retains Verify, errors, enabled retry');
        await click('form:has(#code) button[type=button]'); await wait(`!!document.querySelector('#email')`);
        check(await evaluate(`document.querySelector('#email').value==='presenter@iches.test'`), 'Resend preserves email');
        await click('form:has(#email) button:not([type=button])'); await wait(`!!document.querySelector('#code') || !!document.querySelector('[role=alert]')`);
        if (await evaluate(`!!document.querySelector('#code')`)) check(await evaluate(`!document.querySelector('[role=alert]')`), 'Resend returns to Verify and clears prior validation');
        else check(await evaluate(`!!document.querySelector('#email[aria-invalid=true]')`), 'Resend throttle retains Request error feedback');
        await navigate('/auth/magic', `!!document.querySelector('form button.auth-button')`);
        await screenshot('magic'); await click('form button.auth-button'); await wait(`!!document.querySelector('[role=alert]')`);
        check(await evaluate(`location.pathname==='/auth/magic' && !!document.querySelector('a[href="/login"]')`), 'Magic invalid confirmation reports error with new-code link');
        }
        if (process.env.ICHES_QA_AUTH_ONLY) {
            check(exceptions.length === 0, 'No uncaught browser exceptions');
            report.result = 'PASS';
            console.log(JSON.stringify({ result: report.result, checks: report.checks.length, measurements: report.measurements.length }));
            return;
        }
        if (process.env.ICHES_QA_PROFILE) {
            await navigate('/dashboard', `!!document.querySelector('#context-title')`);
        } else {
        await navigate('/dev/login', `!!document.querySelector('#password')`); await screenshot('demo');
        await fill('#email', 'presenter@iches.test'); await fill('#password', 'password'); await key('Enter');
        await wait(`location.pathname==='/dashboard' && !!document.querySelector('#context-title')`);
        check(true, 'Existing local demo login redirects to dashboard');
        }
        for (const lang of ['en', 'id', 'ar']) {
            if (lang !== 'en') await locale(lang);
            for (const width of [390, 768, 1440, 1920]) { await viewport(width); await measure('dashboard', lang, width); }
            await evaluate(`document.querySelector('aside button').focus()`); await key('Enter');
            await sleep(150);
            report.measurements.push(await evaluate(`({kind:'collapsed-sidebar',width:document.querySelector('aside').getBoundingClientRect().width,expanded:document.querySelector('aside button').getAttribute('aria-expanded')})`));
            await wait(`document.querySelector('aside').getBoundingClientRect().width===80`);
            check(await evaluate(`document.querySelector('aside').getBoundingClientRect().width===80`), `${lang}: desktop collapse`);
            await evaluate(`document.querySelector('aside button').focus()`); await key('Enter');
            await wait(`document.querySelector('aside').getBoundingClientRect().width===256`);
            check(await evaluate(`document.querySelector('aside').getBoundingClientRect().width===256`), `${lang}: desktop expand`);
            await viewport(390, 360); await click('button[aria-haspopup=dialog]');
            await wait(`!!document.querySelector('[role=dialog]')`);
            const sheet = await evaluate(`(()=>{const d=document.querySelector('[role=dialog]'),r=d.getBoundingClientRect();return {portal:!d.closest('.iches-backoffice'),focus:d.contains(document.activeElement),background:getComputedStyle(d).backgroundColor,right:r.right,left:r.left,inner:getComputedStyle(d.querySelector('nav').parentElement.parentElement).backgroundColor}})()`);
            report.measurements.push({ kind: 'sheet', lang, ...sheet });
            check(sheet.portal && sheet.focus, `${lang}: Sheet portal and initial focus`);
            check(['oklch(1 0 0)', 'oklch(0.985 0 0)'].includes(sheet.background) && sheet.inner === 'rgba(0, 0, 0, 0)', `${lang}: white Sheet without nested green panel`);
            check(Math.abs(lang === 'ar' ? sheet.right - 390 : sheet.left) < 1, `${lang}: Sheet side matches direction`);
            for (let i = 0; i < 6; i++) { await key('Tab'); check(await evaluate(`!!document.activeElement.closest('[role=dialog]')`), `${lang}: native Tab remains trapped ${i}`); }
            await key('Tab', 'Tab', 1); check(await evaluate(`!!document.activeElement.closest('[role=dialog]')`), `${lang}: native Shift-Tab remains trapped`);
            await screenshot(`sheet-${lang}`); await key('Escape'); await wait(`!document.querySelector('[role=dialog]')`);
            check(await evaluate(`document.activeElement===document.querySelector('button[aria-haspopup=dialog]')`), `${lang}: native Escape restores trigger focus`);
            await click('button[aria-haspopup=dialog]'); await wait(`!!document.querySelector('[role=dialog]')`);
            await click('[role=dialog] button'); await wait(`!document.querySelector('[role=dialog]')`);
            check(await evaluate(`document.activeElement===document.querySelector('button[aria-haspopup=dialog]')`), `${lang}: close button restores focus`);
            await click('button[aria-haspopup=menu]'); await wait(`!!document.querySelector('[role=menu]')`);
            await key('ArrowDown'); check(await evaluate(`!!document.activeElement.closest('[role=menu]')`), `${lang}: native account menu keyboard navigation`);
            await screenshot(`account-${lang}`); await key('Escape'); await wait(`!document.querySelector('[role=menu]')`);
        }
        const router = `document.querySelector('#app').__vue_app__.config.globalProperties.$inertia`;
        await evaluate(`${router}.visit('/')`); await wait(`location.pathname==='/' && !!document.querySelector('main')`);
        await evaluate('history.back()'); await wait(`location.pathname==='/dashboard' && !!document.querySelector('#context-title')`);
        check(await evaluate(`document.documentElement.lang==='ar' && document.documentElement.dir==='rtl'`), 'Back restores Arabic dashboard');
        await locale('en'); await evaluate('history.forward()'); await wait(`location.pathname==='/'`);
        check(await evaluate(`document.documentElement.lang==='ar'`), 'Forward restores saved Arabic public locale');
        await evaluate('history.back()'); await wait(`location.pathname==='/dashboard' && document.documentElement.lang==='en'`);
        const workspace = await evaluate(`document.querySelector('option[value^="assignment:"]')?.value`);
        check(!!workspace, 'Existing role-scoped demo workspace available');
        await evaluate(`(()=>{window.qaFinished=false;document.addEventListener('inertia:finish',()=>window.qaFinished=true,{once:true});const s=document.querySelector('select:has(option[value^="assignment:"])');s.value=${JSON.stringify(workspace)};s.dispatchEvent(new Event('change',{bubbles:true}));})()`);
        await click('form:has(option[value^="assignment:"]) button'); await wait('window.qaFinished');
        check(await evaluate(`fetch('/me/workspaces').then(r=>r.json()).then(d=>d.activeWorkspace===${JSON.stringify(workspace)})`), 'Workspace selection persisted on server');
        check(await evaluate(`document.body.textContent.includes('presenter@iches.test')`), 'Workspace preserves actor identity');
        await click('button[aria-haspopup=menu]'); await wait(`!!document.querySelector('[role=menuitem]')`);
        await click('[role=menuitem]'); await wait(`location.pathname==='/login' && !!document.querySelector('#email')`);
        await navigate('/dashboard', `location.pathname==='/login' && !!document.querySelector('#email')`);
        check(true, 'Account logout and authenticated route protection');
        check(exceptions.length === 0, 'No uncaught browser exceptions');
        report.result = 'PASS';
        console.log(JSON.stringify({ result: report.result, checks: report.checks.length, measurements: report.measurements.length }));
    } catch (error) { report.result = 'FAIL'; report.error = error.message; throw error; }
    finally {
        fs.writeFileSync(path.join(output, process.env.ICHES_QA_DASHBOARD_ONLY ? 'dashboard-results.json' : 'browser-results.json'), JSON.stringify(report, null, 2));
        if (send && ws?.readyState === WebSocket.OPEN) await send('Browser.close').catch(() => {});
        ws?.close();
        // Leave the unique disposable profile in OS temp; never touch the owner's profile.
        for (let i = 0; i < 20 && chrome.exitCode === null; i++) await sleep(100);
        if (chrome.exitCode === null) chrome.kill();
    }
}
main().catch(error => { console.error(error.message); process.exitCode = 1; });
