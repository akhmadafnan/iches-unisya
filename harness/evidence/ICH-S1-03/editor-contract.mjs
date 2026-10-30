// DB-free behavior checks against the actual TypeScript modules; no app/DB server involved.
import assert from 'node:assert/strict';
import fs from 'node:fs';
import { execFileSync } from 'node:child_process';
import ts from 'typescript';

async function module(path) {
    const code = ts.transpileModule(fs.readFileSync(path, 'utf8'), { compilerOptions: { target: ts.ScriptTarget.ES2022, module: ts.ModuleKind.ESNext } }).outputText;
    return import(`data:text/javascript;base64,${Buffer.from(code).toString('base64')}`);
}
const editor = await module('resources/js/lib/cms/editor.ts');
const api = await module('resources/js/lib/cms/api.ts');
const { cmsEligible } = await module('resources/js/types.ts');
const php = process.env.ICHES_PHP_EXE ?? (process.platform === 'win32' ? `${process.env.USERPROFILE}\\.config\\herd\\bin\\php84\\php.exe` : 'php');
const catalog = JSON.parse(execFileSync(php, ['-r', "require 'vendor/autoload.php'; echo json_encode((new App\\Services\\Cms\\PageSnapshotValidator)->catalog());"], { encoding: 'utf8' }));
const snapshots = [];
let state = editor.emptySnapshot();
for (const [key, definition] of Object.entries(catalog)) {
    state = editor.addSection(state, catalog, key);
    const section = state.sections.at(-1);
    for (const locale of editor.locales) {
        editor.setStatus(section.locales, locale, 'ready', Object.keys(definition.fields));
        for (const [field, type] of Object.entries(definition.fields)) section.locales[locale].content[field] = type === 'url' ? '/conference' : locale === 'ar' ? 'معلومات المؤتمر' : 'Conference information';
    }
}
const original = JSON.stringify(state);
state = editor.changeSection(state, 0, 'duplicate');
assert.equal(new Set(state.sections.map(s => s.id)).size, 6);
assert.notEqual(state.sections[0].id, state.sections[1].id);
assert.deepEqual(state.sections[0].locales, state.sections[1].locales);
state.sections[1].locales.en.content.body = 'Independent duplicate';
assert.notEqual(state.sections[0].locales.en.content.body, state.sections[1].locales.en.content.body);
state = editor.changeSection(state, 1, 'down');
state = editor.changeSection(state, 2, 'up');
state = editor.changeSection(state, 1, 'delete');
assert.equal(JSON.stringify(state.sections.map(s => s.position)), '[0,1,2,3,4]');
assert.equal(JSON.parse(original).sections.length, 5);
const countdown = state.sections.find(s => s.component_key === 'countdown');
assert.deepEqual(countdown.bindings, [{ source: 'event', field: 'starts_at' }]);
for (const locale of editor.locales) {
    editor.setStatus(state.locales, locale, 'ready', ['title']);
    state.locales[locale].content.title = locale === 'ar' ? 'المؤتمر' : 'Conference';
}
assert.equal(editor.canPublish(state), true);
snapshots.push(editor.copy(state));
editor.setStatus(state.locales, 'ar', 'missing', ['title']);
assert.equal(state.locales.ar.content, null);
state.sections[0].visible = false;
snapshots.push(state);
let bounded = editor.emptySnapshot();
for (let i = 0; i < 101; i++) bounded = editor.addSection(bounded, catalog, 'hero');
assert.equal(bounded.sections.length, 100);
assert.equal(editor.changeSection(bounded, 0, 'duplicate').sections.length, 100);
assert.equal(new Set(bounded.sections.map(s => s.id)).size, 100);
assert.equal(editor.validField('<script>unsafe</script>', 'text', 'draft'), false);
assert.equal(editor.validField('&#x3c;script&#x3e;', 'text', 'ready'), false);
for (const url of ['javascript:alert(1)', '//evil.test', 'https://user:pass@example.test', '/bad%0d%0a', 'http://example.test']) assert.equal(editor.validField(url, 'url', 'ready'), false);
for (const url of ['/info', 'https://example.test/info']) assert.equal(editor.validField(url, 'url', 'ready'), true);
assert.equal(editor.validField('', 'text', 'ready'), false);
assert.equal(editor.validField('a'.repeat(5001), 'text', 'draft'), false);
assert.equal(editor.validField('معلومات المؤتمر', 'text', 'ready'), true);
const workspace = { key: 'assignment:1', role: 'superadmin', event: 'Synthetic', institution: null };
assert.equal(cmsEligible([workspace], 'assignment:1'), true);
for (const role of ['participant', 'presenter', 'reviewer', 'cohost_pic']) assert.equal(cmsEligible([{ ...workspace, role }], 'assignment:1'), false);
assert.equal(cmsEligible([{ ...workspace, institution: 'Synthetic' }], 'assignment:1'), false);
assert.equal(cmsEligible([workspace], 'personal'), false);
assert.equal(cmsEligible([workspace], 'assignment:2'), false);
globalThis.document = { cookie: 'XSRF-TOKEN=synthetic%3Dcsrf' };
for (const status of [401, 403, 409, 419, 422, 503]) {
    globalThis.fetch = async (url, options) => {
        assert.equal(options.headers['X-XSRF-TOKEN'], 'synthetic=csrf');
        assert.equal(options.headers.Accept, 'application/json');
        assert.equal(options.credentials, 'same-origin');
        assert.equal(options.cache, 'no-store');
        assert.equal(options.redirect, 'error');
        assert.equal(options.method, 'POST');
        assert.deepEqual(JSON.parse(options.body), { snapshot: state });
        return { ok: false, status, json: async () => ({ errors: { snapshot: ['Synthetic rejection'] } }) };
    };
    try { await api.cmsRequest('/synthetic-fixture', { snapshot: state }); assert.fail('Failure accepted'); }
    catch (error) { assert.equal(api.cmsErrorKey(error), `cms.errors.${status}`); assert.deepEqual(error.fields, { snapshot: ['Synthetic rejection'] }); }
}
globalThis.fetch = async (_, options) => { assert.equal(options.method, 'GET'); return { ok: true, json: async () => ({ snapshot: state }) }; };
assert.deepEqual((await api.cmsRequest('/synthetic-fixture')).snapshot, state);
globalThis.fetch = async () => { throw new TypeError('Synthetic network error'); };
try { await api.cmsRequest('/synthetic-fixture'); assert.fail(); } catch (error) { assert.equal(api.cmsErrorKey(error), 'cms.errors.network'); }
// Validate the frontend's real output against PHP schema v1, without booting Laravel or any DB.
const validation = execFileSync(php, ['-r', "require 'vendor/autoload.php'; foreach (json_decode(stream_get_contents(STDIN), true) as $snapshot) { (new App\\Services\\Cms\\PageSnapshotValidator)->validate($snapshot); } echo 'PHP v1 accepts frontend snapshots';"], { input: JSON.stringify(snapshots), encoding: 'utf8' });
for (const locale of ['en', 'id', 'ar']) {
    const messages = (await module(`resources/js/i18n/locales/${locale}.ts`)).default.cms;
    const keys = value => Object.entries(value).flatMap(([key, entry]) => typeof entry === 'object' ? keys(entry).map(child => `${key}.${child}`) : [key]).sort();
    const english = (await module('resources/js/i18n/locales/en.ts')).default.cms;
    assert.deepEqual(keys(messages), keys(english));
}
console.log('PASS: section CRUD/order/UUID/limits, EN-ID-AR snapshots, PHP v1 compatibility, navigation scope, CSRF transport, six error statuses, network error, locale parity.');
console.log(validation);
