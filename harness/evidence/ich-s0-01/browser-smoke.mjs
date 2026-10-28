// Task-scoped Herd browser check. Uses installed Chrome and Node's built-in CDP client.
import assert from 'node:assert/strict';
import { spawn } from 'node:child_process';
import { once } from 'node:events';
import { access, mkdtemp, readFile, writeFile } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import { setTimeout as delay } from 'node:timers/promises';

const chrome = process.env.CHROME_PATH ?? 'C:/Program Files/Google/Chrome/Application/chrome.exe';
await access(chrome);
const profile = await mkdtemp(join(tmpdir(), 'iches-s0-01-browser-'));
const browser = spawn(chrome, [
    '--headless=new', '--no-first-run', '--no-default-browser-check',
    '--remote-debugging-port=0', '--remote-debugging-address=127.0.0.1',
    `--user-data-dir=${profile}`, 'about:blank',
], { windowsHide: true, stdio: 'ignore' });
let socket;
try {
    let port;
    for (let attempt = 0; attempt < 100; attempt++) {
        try {
            port = (await readFile(join(profile, 'DevToolsActivePort'), 'utf8')).split('\n')[0];
            break;
        } catch { await delay(100); }
    }
    assert.ok(port, 'Chrome must expose its isolated debugging port');
    const target = await (await fetch(`http://127.0.0.1:${port}/json/new?about:blank`, { method: 'PUT' })).json();
    socket = new WebSocket(target.webSocketDebuggerUrl);
    await once(socket, 'open');
    let id = 0;
    const pending = new Map();
    const errors = [];
    socket.addEventListener('message', ({ data }) => {
        const message = JSON.parse(data);
        if (message.id) {
            const request = pending.get(message.id);
            pending.delete(message.id);
            if (message.error) request.reject(new Error(JSON.stringify(message.error)));
            else request.resolve(message.result);
        } else if (message.method === 'Runtime.exceptionThrown') {
            errors.push(message.params.exceptionDetails.text);
        } else if (message.method === 'Runtime.consoleAPICalled' && message.params.type === 'error') {
            errors.push('Browser console error');
        } else if (message.method === 'Network.loadingFailed') {
            errors.push(message.params.errorText);
        } else if (message.method === 'Network.responseReceived' && message.params.response.status >= 400) {
            errors.push(`HTTP ${message.params.response.status}: ${message.params.response.url}`);
        }
    });
    const send = (method, params = {}) => new Promise((resolve, reject) => {
        const requestId = ++id;
        pending.set(requestId, { resolve, reject });
        socket.send(JSON.stringify({ id: requestId, method, params }));
    });
    const evaluate = async (expression) => {
        const result = await send('Runtime.evaluate', { expression, returnByValue: true });
        assert.equal(result.exceptionDetails, undefined, 'Browser expression must succeed');
        return result.result.value;
    };
    await send('Page.enable');
    await send('Runtime.enable');
    await send('Network.enable');
    console.log(`Browser: ${(await send('Browser.getVersion')).product}`);
    for (const [name, width, height] of [['desktop', 1440, 900], ['mobile', 390, 844]]) {
        await send('Emulation.setDeviceMetricsOverride', { width, height, deviceScaleFactor: 1, mobile: name === 'mobile' });
        await send('Page.navigate', { url: 'http://iches-unisya.test/' });
        let rendered = false;
        for (let attempt = 0; attempt < 100; attempt++) {
            rendered = await evaluate(`document.querySelector('#app[data-v-app] #foundation-title')?.textContent === 'Application foundation'`);
            if (rendered) break;
            await delay(100);
        }
        assert.ok(rendered, `${name}: Vue must mount the Inertia page`);
        assert.equal(await evaluate('document.documentElement.scrollWidth <= innerWidth'), true, `${name}: no horizontal overflow`);
        assert.equal(await evaluate("getComputedStyle(document.querySelector('main')).display"), 'flex', `${name}: Tailwind must load`);
        assert.equal(await evaluate('document.title'), 'Application foundation | ICHES UNISYA 2027');
        assert.ok(await evaluate("document.body.textContent.includes('ICH-S0-01')"));
        const screenshot = await send('Page.captureScreenshot', { format: 'png' });
        await writeFile(new URL(`./${name}.png`, import.meta.url), Buffer.from(screenshot.data, 'base64'));
        console.log(`PASS ${name} ${width}x${height}: Vue mounted, Tailwind loaded, correct title/task, no overflow`);
    }
    assert.deepEqual(errors, [], 'No browser runtime, console, network, or HTTP errors');
    console.log('PASS browser errors: 0');
} finally {
    socket?.close();
    browser.kill();
}
