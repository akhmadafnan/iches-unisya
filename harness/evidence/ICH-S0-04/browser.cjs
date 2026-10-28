const fs = require('node:fs');
const path = require('node:path');
const { chromium } = require(process.env.LOCALAPPDATA + '/npm-cache/_npx/e41f203b7505f1fb/node_modules/playwright');
(async () => {
 const root = process.cwd();
 const out = path.join(root, 'harness/evidence/ICH-S0-04');
 fs.mkdirSync(out, {recursive:true});
 const manifest = JSON.parse(fs.readFileSync('public/build/manifest.json'))['resources/js/app.ts'];
 const browser = await chromium.launch({headless:true});
 const page = await browser.newPage(); page.setDefaultTimeout(8000); console.log("Browser launched");
 const errors=[]; page.on('pageerror', e => errors.push(e.message));
 let active='personal', switched=false;
 const payload = () => ({component:'dashboard/Index',props:{errors:{},account:{name:'Synthetic Member',email:'synthetic@example.invalid'},workspaces:[{key:'personal',label:'Personal'},{key:'assignment:1',label:'Synthetic Event / Synthetic Institution / cohost_pic'}],activeWorkspace:active},url:'/dashboard',version:null,clearHistory:false,encryptHistory:false});
 await page.route('http://workspace.test/**', async route => {
  const req=route.request(), url=new URL(req.url());
  if (url.pathname.startsWith('/build/')) {
   const file=path.join(root,'public',url.pathname);
   return route.fulfill({body:fs.readFileSync(file),contentType:file.endsWith('.css')?'text/css':'text/javascript'});
  }
  if (req.method()==='POST') { active=JSON.parse(req.postData()).workspace; switched=true; }
  if (req.headers()['x-inertia']) return route.fulfill({json:payload(),headers:{'X-Inertia':'true'}});
  const data=JSON.stringify(payload()).replace(/&/g,'&amp;').replace(/'/g,'&#39;');
  return route.fulfill({contentType:'text/html',body:`<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="/build/${manifest.css[0]}"></head><body><div id="app" data-page='${data}'></div><script type="module" src="/build/${manifest.file}"></script></body></html>`});
 });
 await page.setViewportSize({width:1440,height:1000});
 await page.goto('http://workspace.test/dashboard');
 await page.getByLabel('Switch workspace', {exact:true}).waitFor();
 await page.keyboard.press('Tab');
 if (await page.locator(':focus').getAttribute('id') !== 'workspace') throw Error('Select not keyboard focusable');
 await page.getByLabel('Switch workspace', {exact:true}).selectOption('assignment:1');
 await page.keyboard.press('Tab'); await page.keyboard.press('Enter');
 console.log('Keyboard submitted', active); await page.waitForFunction(() => document.querySelector('[role=status]').textContent.includes('cohost_pic'));
 if (!switched) throw Error('Keyboard did not submit');
 await page.screenshot({path:path.join(out,'desktop.png'),fullPage:true});
 for (const rtl of [false,true]) {
  await page.setViewportSize({width:375,height:812});
  await page.evaluate(rtl => {document.documentElement.dir=rtl?'rtl':'ltr';},rtl);
  if (await page.evaluate(() => document.documentElement.scrollWidth > innerWidth)) throw Error('Horizontal overflow');
  await page.screenshot({path:path.join(out,rtl?'mobile-rtl.png':'mobile.png'),fullPage:true});
 }
 if (errors.length) throw Error(errors.join('\n'));
 console.log('PASS: compiled dashboard fixture; keyboard switch POST, identity visible, desktop 1440px, mobile 375px, RTL, no overflow or browser errors. HTTP backend authorization verified separately by MySQL tests.');
 await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
