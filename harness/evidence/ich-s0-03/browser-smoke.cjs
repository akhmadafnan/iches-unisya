// Run with PLAYWRIGHT_MODULE pointing to an already-installed Playwright module.
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const fs = require('node:fs');
const assert = require('node:assert/strict');
const path = require('node:path');

(async () => {
    const browser = await chromium.launch();
    const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
    const page = await context.newPage();
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    const root = process.env.ICHES_SMOKE_URL || 'http://iches-unisya.test';
    const output = __dirname;
    try {
        await page.goto(root + '/dashboard');
        await page.waitForURL('**/login');
        await page.screenshot({ path: path.join(output, 'login-desktop.png'), fullPage: true });
        await page.setViewportSize({ width: 390, height: 844 });
        assert(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth));
        await page.getByLabel('Name (optional for new accounts)').focus();
        await page.keyboard.press('Tab');
        assert.equal(await page.locator('#email').evaluate(el => el === document.activeElement), true);
        await page.screenshot({ path: path.join(output, 'login-mobile.png'), fullPage: true });

        const logPath = path.join(process.cwd(), 'storage/logs/laravel.log');
        const offset = fs.existsSync(logPath) ? fs.statSync(logPath).size : 0;
        await page.locator('#email').fill('participant@iches.test');
        await page.getByRole('button', { name: 'Send / resend code' }).click();
        await page.getByRole('status').waitFor();
        const mail = fs.readFileSync(logPath).subarray(offset).toString();
        const code = mail.match(/code: ([0-9]{6})/)[1];
        assert(mail.includes('participant@iches.test'));
        assert(mail.includes('/auth/magic/'));
        const beforeLogin = (await context.cookies()).find(c => c.name.includes('session'));
        await page.locator('#verify-email').fill('participant@iches.test');
        await page.getByLabel('Six-digit code').fill(code);
        await page.getByRole('button', { name: 'Verify and sign in' }).click();
        await page.waitForURL('**/dashboard');
        const signedInCookies = await context.cookies();
        assert.notEqual(signedInCookies.find(c => c.name.includes('session')).value, beforeLogin.value);
        await page.screenshot({ path: path.join(output, 'dashboard-mobile.png'), fullPage: true });
        await page.setViewportSize({ width: 1280, height: 900 });
        await page.screenshot({ path: path.join(output, 'dashboard-desktop.png'), fullPage: true });
        await page.getByRole('button', { name: 'Sign out' }).click();
        await page.waitForURL('**/login');

        // Replaying the old authenticated cookie must fail after logout.
        const replay = await browser.newContext();
        await replay.addCookies(signedInCookies);
        const oldSession = await replay.newPage();
        await oldSession.goto(root + '/dashboard');
        await oldSession.waitForURL('**/login');
        await replay.close();
        console.log('PASS: OTP/mail-log, session rotation, logout/old-session rejection, desktop/mobile and keyboard.');

        await page.goto(root + '/dev/login');
        await page.getByLabel('Demo email').fill('admin@iches.test');
        await page.getByLabel('Password', { exact: true }).fill('incorrect');
        await page.getByRole('button', { name: 'Sign in', exact: true }).click();
        await page.getByRole('alert').waitFor();
        await page.getByLabel('Password', { exact: true }).fill('password');
        await page.getByRole('button', { name: 'Sign in', exact: true }).click();
        await page.waitForURL('**/dashboard');
        await page.getByRole('button', { name: 'Sign out' }).click();
        await page.waitForURL('**/login');
        console.log('PASS: demo login and validation.');

        await page.goto(root + '/login');
        const magicOffset = fs.statSync(logPath).size;
        await page.locator('#email').fill('reviewer@iches.test');
        await page.getByRole('button', { name: 'Send / resend code' }).click();
        await page.getByRole('status').waitFor();
        const magicMail = fs.readFileSync(logPath).subarray(magicOffset).toString();
        const magicUrl = magicMail.match(/http[^\s]+\/auth\/magic\/[a-f0-9]{64}/)[0];
        await page.goto(magicUrl);
        await page.waitForURL('**/auth/magic');
        assert(!(await page.content()).includes(magicUrl.split('/').pop()));
        await page.getByRole('button', { name: 'Confirm and sign in' }).click();
        await page.waitForURL('**/dashboard');
        await page.getByRole('button', { name: 'Sign out' }).click();
        await page.waitForURL('**/login');
        await page.goto(magicUrl);
        await page.getByRole('button', { name: 'Confirm and sign in' }).click();
        await page.getByRole('alert').waitFor();
        assert.equal(new URL(page.url()).pathname, '/auth/magic');
        assert.deepEqual(errors, []);
        console.log('PASS: Herd OTP/mail-log, Magic Link confirmation/replay, demo validation/login, old-session rejection, desktop/mobile overflow and keyboard; no browser runtime errors.');
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error.name + ': ' + error.message.replace(/\/auth\/magic\/[a-f0-9]{64}/g, '/auth/magic/[REDACTED]')); process.exitCode = 1; });
