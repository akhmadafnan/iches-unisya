# ICH-UI-01C — local verification, 29 October 2026

Owner GO CODING ICH-UI-01C supersedes the Work Order's historical PREPARED state. No publication or merge authorization is included.

Machine DESKTOP-PE5D86C; repository D:\PINJAM-AFNAN\Herd\iches-unisya; remote https://github.com/akhmadafnan/iches-unisya.git. Preflight PASS: clean main and HEAD = locally tracked origin/main = 584e6c4937d09023efa28620ef662f984a641867. No fetch or synchronization performed. Feature branch feat/ich-ui-01c-lyra-primitives. The single local commit must have that direct parent; its full SHA is provided in the owner handoff.

## Implementation and scope

Seven new application paths, all within the eight-path maximum:

- resources/js/components/ui/Input.vue
- resources/js/components/ui/Textarea.vue
- resources/js/components/ui/Label.vue
- resources/js/components/ui/Badge.vue
- resources/js/components/ui/NativeSelect.vue
- resources/js/components/ui/DialogContent.vue
- resources/js/components/ui/Table.vue

Existing Button and SheetContent remain unchanged, including variants, as/asChild, native attributes and 44px shell targets. No dependencies were added. Manifest/lock, config, CSS, fonts, app lifecycle, auth, layout, navigation, routes, backend, database and governance remain unchanged. Additional changed paths are only the authorized append-only harness/build-log.md and this task's evidence directory; changed-paths.txt contains the complete inventory.

Primitives adapt the pinned official reka-lyra registry to single-file components. See registry-reference.json and registry-LICENSE.txt. Input/Textarea/NativeSelect use Vue defineModel and native directives, including native numeric/multiple handling; no custom parser or form library. Label is a native label. NativeSelect retains the platform arrow and native option/optgroup slots. Badge provides text slots and four standard presentational variants. Table forwards attributes to its semantic table, with a bounded horizontal overflow wrapper.

DialogContent composes Reka Portal/Overlay/Content, forwards typed props and emits, and bounds scrolling to the viewport. Consumers compose DialogRoot/Trigger/Title/Description/Close from Reka; this wrapper deliberately has no hardcoded English-only close label. No added animations, decorative styling or business UI. New primitives are tested through the fixture; no claim that a CMS page now uses them.

## Actual verification

Local Node 26.4.0, PHP 8.4.24, Vite 7.3.7, installed Chrome. No global toolchain or Laravel Herd changes. PHP tests use the existing phpunit.xml DB_CONNECTION=disabled configuration. Node 22 CI is a later publication gate and was not run.

| Command | Exit | Actual result |
|---|---:|---|
| npm.cmd run typecheck | 0 | PASS |
| npm.cmd run build | 0 | PASS, 3382 application modules; build.txt |
| php artisan test --compact --no-ansi | 0 | PASS, 30 tests / 684 assertions; php-tests.txt |
| git diff --check | 0 | PASS; repeated for staged new files before commit |
| node_modules/.bin/vue-tsc.cmd --noEmit -p harness/evidence/ICH-UI-01C/tsconfig.json | 0 | PASS, typed fixture consumers and application source |
| node harness/evidence/ICH-UI-01C/fixture-server.mjs | Build completed | PASS, Vite production fixture build, 664 modules; temporary localhost static server |
| ICHES_BROWSER_QA=1 node harness/evidence/ICH-UI-01C/browser-fixture.cjs | 0 | PASS, 180 checks; fixture-results/results.json |
| Existing harness/evidence/ICH-UI-01B/browser.cjs with output redirected to this task | 0 | PASS, 196 current shell/auth regression checks; shell-regression/browser-results.json |
| Public/Auth deep comparison and protected-file audit | 0 | PASS; isolation-comparison.json |

The four mandatory final commands ran after the last application edit. No application changes followed them. Evidence text logs preserve command output with line endings and trailing whitespace normalized for Git. No PHP source changes required Pint.

## Browser evidence and methods

Fixture: index.html, fixture.ts, Fixture.vue and fixture.css, built by fixture-server.mjs into a unique OS temporary directory and served only at 127.0.0.1:8012. No Laravel route, production page import, public/hot, or app config change. The initial Vite dev-server approach did not respond; switching the evidence server to a Vite production build resolved it without an application change. Temporary server is stopped after QA.

Fixture results: native CDP keyboard delivery was positively detected through a trusted Tab event. Real CDP Tab moved Input to Textarea, ArrowDown changed native select/model, and Tab/Shift+Tab/Escape verified dialog wrapping/dismissal/restoration in EN/ID/AR at 390/768/1440. Direct outside focus was trapped; background was aria-hidden; native labels focused controls; invalid/focus/disabled/readonly states, numeric and multiple models, parent updates and event/native attrs were checked. Dialog emitted openAutoFocus and escapeKeyDown, used a real body portal, inherited Admin font/direction, fitted all viewports and scrolled for long content at 390x400. Outside pointer dismissal used a dispatched PointerEvent. Tables retained headers/cells/attrs with intentional inner horizontal overflow and no document overflow. Badge variants all contained visible text.

fixture-results/ contains full fixture screenshots for EN/ID/AR at 390/768/1440, matching dialog screenshots, a long-dialog screenshot and the exact assertion list. Rendered platform-font inspection verified JetBrains Mono, Geist Mono and Arabic Noto fallback. This is automated native CDP keyboard QA, not manual human UAT or a screen-reader audit.

Shell/auth regression used http://iches-unisya.test and existing synthetic local demo data, with no seeding, account provisioning or database reset. It checked login/dashboard/workspace/actor/logout/guest protection, EN/ID/AR same-document locale requests, lang/dir/history/reload, existing dropdown and mobile Sheet portals/focus, fonts and 390/768/1440 overflow. That existing regression script uses DOM focus/click/Escape dispatch, not native keyboard input; native keyboard evidence belongs to the new fixture. Reproduction requires ICHES_BROWSER_QA=1, ICHES_QA_OUTPUT=harness/evidence/ICH-UI-01C/shell-regression, and the existing synthetic demo password supplied privately via ICHES_QA_DEMO_PASSWORD. No credential/cookie/token is stored in this task's evidence.

isolation-comparison.json proves Public/Auth body/main/h1/input/button computed colors, fonts, sizes, borders and radii match the measured UI-01B baseline. It also checks protected config/CSS/font-import/Button/Sheet/manifests against the current Git baseline, normalizing Git's CRLF/LF representation. Public is the existing Foundation placeholder, not a completed CMS website.

## Acceptance matrix

| ID | Status | Evidence |
|---|---|---|
| AC-01 | PASS | Expected clean main/device/remote/base; feature branch and single direct-parent commit verified |
| AC-02 | PASS | Config/theme/font files unchanged; shell computed token and font checks |
| AC-03 | PASS | Seven new application paths within allowlist, zero dependency/CSS/global changes; inventory audit |
| AC-04 | PASS | Existing Button and Sheet unchanged; Button variants/as/asChild/disabled/44px and shell navigation/dropdown/Sheet checks |
| AC-05 | PASS | Typed fixture; native label/model/attrs, parent updates, number/multiple values, required/invalid/disabled checks |
| AC-06 | PASS | Four text-bearing Badge variants inspected in the fixture; no business status logic |
| AC-07 | PASS | Native CDP Tab/Shift+Tab/Escape; trap/restore, real portal, modal accessible name/description, background hiding, scrolling and RTL |
| AC-08 | PASS | Native semantic table/caption/8 column headers/24 cells/attrs; constrained horizontal overflow at 390 |
| AC-09 | PASS | Rendered JetBrains/Geist/Noto platform fonts plus unchanged Public/Auth computed styles |
| AC-10 | PASS | Fresh 196-check existing application regression on Herd; no new CMS usage claimed |
| AC-11 | PASS | Fixture EN/ID/AR and 390/768/1440, native keyboard/focus, dialog bounds and no document overflow |
| AC-12 | PASS | Final mandatory checks exit 0; staged audit and clean single local commit verified |

No unresolved implementation blocker. Manual human keyboard/screen-reader UAT and Node 22 CI are unperformed limits. Independent review/owner acceptance, push/PR and subsequent gates remain separate. Stop after the local commit; no push, PR, merge, migration, seeding, deployment, branch deletion, post-merge sync, UI-01D or S1 work.
