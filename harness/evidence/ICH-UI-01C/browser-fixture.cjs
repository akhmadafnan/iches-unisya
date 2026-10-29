// Opt-in local fixture QA using the installed Chrome and Node's native WebSocket.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const os = require('node:os');
const { spawn } = require('node:child_process');
const { once } = require('node:events');
const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));

async function main() {
    assert.equal(process.env.ICHES_BROWSER_QA, '1');
    assert.equal(os.hostname().toUpperCase(), 'DESKTOP-PE5D86C');
    assert.equal(fs.realpathSync('.').toLowerCase(), 'd:\\pinjam-afnan\\herd\\iches-unisya');
    const output = path.resolve('harness/evidence/ICH-UI-01C/fixture-results');
    fs.mkdirSync(output, { recursive: true });
    const profile = fs.mkdtempSync(path.join(os.tmpdir(), 'iches-ui01c-'));
    const chrome = spawn('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe', [
        '--headless=new', '--remote-debugging-port=0', `--user-data-dir=${profile}`,
        '--no-first-run', '--no-default-browser-check', 'about:blank',
    ], { windowsHide: true, stdio: 'ignore' });
    const report = { result: 'RUNNING', checks: [], limits: [], cases: [], fonts: [] };
    let ws, send;
    const check = (actual, expected, message) => { assert.deepEqual(actual, expected, message); report.checks.push(message); };
    try {
        const portFile = path.join(profile, 'DevToolsActivePort');
        for (let i = 0; i < 100 && !fs.existsSync(portFile); i++) await sleep(100);
        const port = Number(fs.readFileSync(portFile, 'utf8').split('\n')[0]);
        const tabs = await (await fetch(`http://127.0.0.1:${port}/json`)).json();
        ws = new WebSocket(tabs.find(tab => tab.type === 'page').webSocketDebuggerUrl);
        await once(ws, 'open');
        let sequence = 0;
        const pending = new Map(), exceptions = [];
        ws.addEventListener('message', event => {
            const message = JSON.parse(event.data);
            if (!message.id) { if (message.method === 'Runtime.exceptionThrown') exceptions.push(message.params.exceptionDetails.text); return; }
            const request = pending.get(message.id); pending.delete(message.id);
            if (message.error) request.reject(new Error(message.error.message)); else request.resolve(message.result);
        });
        send = (method, params = {}) => new Promise((resolve, reject) => {
            const id = ++sequence; pending.set(id, { resolve, reject }); ws.send(JSON.stringify({ id, method, params }));
        });
        const evaluate = async expression => {
            const r = await send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
            if (r.exceptionDetails) throw new Error(r.exceptionDetails.text + ': ' + r.result.description);
            return r.result.value;
        };
        const wait = async expression => {
            for (let i = 0; i < 100; i++) { if (await evaluate(expression)) return; await sleep(50); }
            throw new Error('Browser wait timed out: ' + expression);
        };
        const click = selector => evaluate(`(()=>{const e=document.querySelector(${JSON.stringify(selector)});e.focus();e.click()})()`);
        const input = (selector, value, event = 'input') => evaluate(`(()=>{const e=document.querySelector(${JSON.stringify(selector)});e.value=${JSON.stringify(value)};e.dispatchEvent(new Event(${JSON.stringify(event)},{bubbles:true}))})()`);
        const model = () => evaluate("JSON.parse(document.querySelector('#model-state').textContent)");
        const screenshot = async (name, full = false) => {
            const params = { format: 'png', captureBeyondViewport: true };
            if (full) { const {cssContentSize:s} = await send('Page.getLayoutMetrics'); params.clip = {x:0,y:0,width:s.width,height:s.height,scale:1}; }
            const r = await send('Page.captureScreenshot', params);
            fs.writeFileSync(path.join(output, name + '.png'), Buffer.from(r.data, 'base64'));
        };
        const fonts = async selector => {
            const {root} = await send('DOM.getDocument');
            const {nodeId} = await send('DOM.querySelector', {nodeId:root.nodeId,selector});
            return (await send('CSS.getPlatformFontsForNode', {nodeId})).fonts.filter(f=>f.glyphCount>0).map(f=>f.familyName);
        };
        await send('Page.enable'); await send('Runtime.enable'); await send('DOM.enable'); await send('CSS.enable');
        await send('Emulation.setDeviceMetricsOverride', {width:1440,height:900,mobile:false,deviceScaleFactor:1});
        await send('Page.navigate', {url:'http://127.0.0.1:8012/harness/evidence/ICH-UI-01C/'});
        await wait('!!window.fixture'); await evaluate('document.fonts.ready');

        // Probe native CDP delivery before selecting the keyboard method for every assertion.
        await send('Page.bringToFront'); await send('Emulation.setFocusEmulationEnabled', {enabled:true});
        await evaluate("window.qaKeys=[];window.addEventListener('keydown',e=>qaKeys.push({key:e.key,trusted:e.isTrusted}));document.querySelector('#name').focus()");
        await send('Input.dispatchKeyEvent',{type:'keyDown',key:'Tab',code:'Tab',windowsVirtualKeyCode:9});
        await send('Input.dispatchKeyEvent',{type:'keyUp',key:'Tab',code:'Tab',windowsVirtualKeyCode:9});
        const native = await evaluate("qaKeys.some(e=>e.key==='Tab'&&e.trusted)");
        report.keyboardMethod = native ? 'Native CDP input' : 'LIMIT: dispatched DOM keyboard events; native CDP delivery unavailable';
        if (!native) report.limits.push('Native Tab/Select default key behavior and manual keyboard UAT are BLOCKED. DOM Tab wrap, Escape handlers, direct focus trap and focus-visible states are verified.');
        const key = async (name, shift = false) => {
            if (native) {
                const vk={Tab:9,Escape:27,ArrowDown:40,ArrowLeft:37,ArrowRight:39}[name];
                for (const type of ['keyDown','keyUp']) await send('Input.dispatchKeyEvent',{type,key:name,code:name,windowsVirtualKeyCode:vk,modifiers:shift?8:0});
            } else await evaluate(`document.activeElement.dispatchEvent(new KeyboardEvent('keydown',{key:${JSON.stringify(name)},code:${JSON.stringify(name)},shiftKey:${shift},bubbles:true,cancelable:true}))`);
            await sleep(100);
        };
        if (native) check(await evaluate("document.activeElement.id"), 'notes', 'Native Tab moves from Input to Textarea');

        check(await evaluate("['name','notes','choice'].every(id=>document.querySelector('label[for='+id+']').control===document.getElementById(id))"),true,'Native label control linkage');
        await click('label[for=name]'); check(await evaluate('document.activeElement.id'),'name','Label click focuses Input');
        check(await evaluate("document.querySelector('#name').matches(':focus-visible') && getComputedStyle(document.querySelector('#name')).boxShadow!=='none'"),true,'Input visible focus ring');
        await input('#name','User fixture'); await input('#notes','Multiline\nfixture'); await input('#number','42'); await input('#choice','b','change');
        await evaluate("document.querySelector('#multiple').options[1].selected=true;document.querySelector('#multiple').dispatchEvent(new Event('change',{bubbles:true}))");
        const values = await model();
        check(values.name,'User fixture','Input update:modelValue'); check(values.notes,'Multiline\nfixture','Textarea update:modelValue');
        check(values.number,42,'Native numeric Input casts model'); check(values.choice,'b','NativeSelect update:modelValue');
        check(values.multiple,['a','b'],'Multiple select array model'); check(values.inputs>0,true,'Input listener forwarded');
        await evaluate("window.fixture.state.name='Parent fixture';window.fixture.state.notes='Parent notes';window.fixture.state.choice='a';window.fixture.state.number=9;window.fixture.state.multiple=['b']");
        await wait("document.querySelector('#name').value==='Parent fixture'");
        check(await evaluate("[document.querySelector('#notes').value,document.querySelector('#choice').value,document.querySelector('#number').value,[...document.querySelector('#multiple').selectedOptions].map(o=>o.value)]"),['Parent notes','a','9',['b']],'Parent-to-native controls synchronization');
        await evaluate("document.querySelector('#notes').focus()"); check((await model()).blurs>0,true,'Native blur listener forwarded');
        check(await evaluate("(()=>{const e=document.querySelector('#name');return [e.name,e.required,e.maxLength,e.getAttribute('aria-describedby'),e.dataset.fixture]})()"),['fixture-name',true,80,'name-help','input'],'Input native and aria attrs forwarded');
        check(await evaluate("(()=>{const e=document.querySelector('#notes');return [e.name,e.rows,e.required,e.maxLength]})()"),['fixture-notes',3,true,500],'Textarea attrs forwarded');
        check(await evaluate("(()=>{const e=document.querySelector('#choice');return [e.name,e.required,e.querySelector('optgroup').label,e.options[2].disabled]})()"),['fixture-choice',true,'Fixture group',true],'NativeSelect options/optgroup/attrs forwarded');
        check(await evaluate("['disabled-input','disabled-textarea','disabled-select'].every(id=>document.getElementById(id).disabled) && document.querySelector('#readonly').readOnly"),true,'Disabled and readonly controls native states');
        check(await evaluate("(()=>{const p=document.createElement('span');p.style.color='var(--destructive)';document.querySelector('main').append(p);const same=getComputedStyle(p).color===getComputedStyle(document.querySelector('#invalid')).borderColor;p.remove();return same})()"),true,'aria-invalid destructive border');
        check(await evaluate("[...document.querySelectorAll('[data-slot=badge]')].every(e=>e.tagName==='SPAN'&&e.textContent.trim().length>0)"),true,'Badge uses semantic text for all four variants');
        check(await evaluate("[...document.querySelectorAll('button[data-variant]')].every(e=>e.getBoundingClientRect().height>=44)"),true,'Existing Button variants retain 44px targets');
        check(await evaluate("[document.querySelector('#button-as').tagName,document.querySelector('#button-child').tagName,document.querySelector('#disabled-button').disabled]"),['A','A',true],'Button as/asChild/disabled compatibility');
        check(await evaluate("document.querySelector('#fixture-table').tagName==='TABLE' && document.querySelectorAll('#fixture-table th[scope=col]').length===8 && document.querySelectorAll('#fixture-table tbody td').length===24 && document.querySelector('#fixture-table').getAttribute('aria-describedby')==='table-help'"),true,'Table semantic native structure and forwarded attrs');
        if (native) {
            await evaluate("document.querySelector('#choice').focus()"); await key('ArrowDown');
            check((await model()).choice,'b','Native Select ArrowDown changes model');
        }

        for (const locale of ['en','id','ar']) {
            await evaluate(`window.fixture.locale=${JSON.stringify(locale)}`);
            await wait(`document.documentElement.lang===${JSON.stringify(locale)}`); await evaluate('document.fonts.ready');
            const bodyFonts = await fonts('main p'), headingFonts = await fonts('h1'); report.fonts.push({locale,bodyFonts,headingFonts});
            check(bodyFonts.some(f=>f.includes('JetBrains')),true,'Rendered body font '+locale);
            check(headingFonts.some(f=>f.includes(locale==='ar'?'Noto Sans Arabic':'Geist Mono')),true,'Rendered heading/Arabic fallback '+locale);
            for (const width of [390,768,1440]) {
                await send('Emulation.setDeviceMetricsOverride',{width,height:900,mobile:width===390,deviceScaleFactor:1}); await sleep(100);
                const layout = await evaluate(`(()=>{const t=document.querySelector('[data-slot=table-container]'),r=t.getBoundingClientRect();return {width:innerWidth,scroll:document.documentElement.scrollWidth,tableWidth:r.width,tableScroll:t.scrollWidth,dir:getComputedStyle(t).direction}})()`);
                check(layout.width,width,'Actual fixture viewport '+locale+'/'+width);
                check(layout.scroll<=width,true,'No page overflow '+locale+'/'+width);
                check(layout.tableWidth<=width,true,'Table overflow remains inside container '+locale+'/'+width);
                check(layout.dir,locale==='ar'?'rtl':'ltr','Native table direction '+locale+'/'+width);
                if(width===390) check(layout.tableScroll>layout.tableWidth,true,'Wide table provides horizontal overflow '+locale);
                report.cases.push({locale,...layout}); await screenshot(locale+'-'+width,true);

                await click('#open-dialog'); await wait("!!document.querySelector('[role=dialog][data-state=open]')"); await sleep(100);
                const dialog = await evaluate(`(()=>{const e=document.querySelector('[role=dialog]'),s=getComputedStyle(e),r=e.getBoundingClientRect();return {role:e.getAttribute('role'),label:!!document.getElementById(e.getAttribute('aria-labelledby')),description:!!document.getElementById(e.getAttribute('aria-describedby')),outside:!e.closest('.iches-backoffice'),dir:s.direction,font:s.fontFamily,primary:s.getPropertyValue('--primary').trim(),inside:r.left>=0&&r.right<=innerWidth&&r.top>=0&&r.bottom<=innerHeight,focus:document.activeElement.id,attr:e.dataset.fixture,backgroundHidden:document.querySelector('#fixture').getAttribute('aria-hidden')}})()`);
                check(dialog.label&&dialog.description,true,'Dialog accessible name/description '+locale+'/'+width);
                check(dialog.outside,true,'Dialog body portal '+locale+'/'+width);
                check(dialog.dir,locale==='ar'?'rtl':'ltr','Dialog direction '+locale+'/'+width);
                check(dialog.inside,true,'Dialog fits viewport '+locale+'/'+width);
                check(dialog.font.includes('JetBrains'),true,'Dialog inherits Admin font '+locale+'/'+width);
                check(dialog.attr,'dialog','Dialog attrs forwarded '+locale+'/'+width);
                check(dialog.backgroundHidden,'true','Modal hides background from accessibility tree '+locale+'/'+width);
                check(dialog.focus,'dialog-input','Dialog initial focus '+locale+'/'+width);
                await evaluate("document.querySelector('#name').focus()"); await sleep(100);
                check(await evaluate("!!document.activeElement.closest('[role=dialog]')"),true,'Modal traps outside focus '+locale+'/'+width);
                await evaluate("document.querySelector('#close-dialog').focus()"); await key('Tab');
                check(await evaluate('document.activeElement.id'),'dialog-input','Dialog Tab wraps '+locale+'/'+width);
                await key('Tab',true); check(await evaluate('document.activeElement.id'),'close-dialog','Dialog Shift+Tab wraps '+locale+'/'+width);
                await screenshot(locale+'-dialog-'+width);
                await key('Escape'); await wait("!document.querySelector('[role=dialog]')"); await sleep(100);
                check(await evaluate('document.activeElement.id'),'open-dialog','Escape restores trigger focus '+locale+'/'+width);
                report.cases.push({locale,width,dialog});
            }
        }
        check((await model()).escapes,9,'Dialog emits escapeKeyDown through wrapper'); check((await model()).opens,9,'Dialog emits openAutoFocus through wrapper');
        await send('Emulation.setDeviceMetricsOverride',{width:390,height:400,mobile:true,deviceScaleFactor:1});
        await evaluate('window.fixture.longDialog=true'); await click('#open-dialog'); await wait("!!document.querySelector('[role=dialog]')");
        check(await evaluate("(()=>{const e=document.querySelector('[role=dialog]'),r=e.getBoundingClientRect();e.scrollTop=e.scrollHeight;return e.scrollHeight>e.clientHeight&&e.scrollTop>0&&r.top>=0&&r.bottom<=innerHeight})()"),true,'Long dialog is viewport-bounded and vertically scrollable');
        await screenshot('ar-long-dialog-390'); await click('#close-dialog'); await wait("!document.querySelector('[role=dialog]')");
        await click('#open-dialog'); await wait("!!document.querySelector('[role=dialog]')");
        await evaluate("document.querySelector('[data-slot=dialog-overlay]').dispatchEvent(new PointerEvent('pointerdown',{bubbles:true,pointerType:'mouse',button:0}))");
        await wait("!document.querySelector('[role=dialog]')");
        check(true,true,'Outside pointer dismisses dialog');
        check(exceptions,[],'No uncaught browser exceptions');
        report.result='PASS'; console.log(JSON.stringify({result:report.result,checks:report.checks.length,keyboard:report.keyboardMethod}));
    } catch (error) { report.result='FAIL'; report.error=error.message; throw error; }
    finally {
        fs.writeFileSync(path.join(output,'results.json'),JSON.stringify(report,null,2)+'\n');
        if(send&&ws?.readyState===WebSocket.OPEN) await send('Browser.close').catch(()=>{});
        ws?.close(); for(let i=0;i<40&&chrome.exitCode===null;i++) await sleep(100);
        if(chrome.exitCode===null)chrome.kill();
        assert.ok(path.resolve(profile).startsWith(path.join(path.resolve(os.tmpdir()),'iches-ui01c-')));
        fs.rmSync(profile,{recursive:true,force:true,maxRetries:10,retryDelay:100});
    }
}
main().catch(error=>{console.error(error.message);process.exitCode=1;});
