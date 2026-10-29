# ICH-UI-01B local handoff — 29 October 2026

Owner explicitly authorized GO CODING ICH-UI-01B, the definitive external Work Order, eleven application paths and one local commit. Machine: DESKTOP-PE5D86C. Repository: D:\PINJAM-AFNAN\Herd\iches-unisya, origin https://github.com/akhmadafnan/iches-unisya.git.

Preflight PASS: clean main; HEAD and locally tracked origin/main both 31d63c147c3ff1066ccaeef1992421d9aac2416b. No fetch/sync performed. Feature branch: feat/ich-ui-01b-theme-isolation. The commit's direct parent must equal that baseline. Full commit SHA is supplied in the owner handoff; it cannot be embedded in its own commit.

## Implementation

- components.json records Reka/Lyra, Neutral, JetBrains Mono/Geist Mono, Phosphor, subtle/default menus and RTL. Actual official strict Vue rawConfigSchema validation passed; no generator/init/apply executed. See schema-validation.json and registry-reference.json for pinned official source URLs and SHA.
- Tailwind inline semantic utilities resolve runtime custom properties. Public/Auth retain their actual baseline colors, font order and radii. Admin has all 32 official Neutral light tokens with the Emerald overlay, including chart/sidebar tokens and radius 0.625rem.
- WorkspaceLayout adds one marker. body:has(.iches-backoffice) supplies the same context to body portals and releases it immediately on Public/Auth navigation. Scoped heading/focus styles use the Admin fonts/ring. Existing shell composition and icons remain.
- Vite/TypeScript aliases agree on resources/js. Existing Button and DashboardProps imports exercise the alias in the real application. cn() uses existing clsx/tailwind-merge.
- Only new application dependencies: @fontsource-variable/jetbrains-mono 5.3.0 and @fontsource-variable/geist-mono 5.3.0. Lockfile matches. npm install used --ignore-scripts; npm audit reported zero vulnerabilities. No backend, DB, governance, routes, tests or existing UI component edits. Inertia initial/navigate/success locale synchronization is unchanged.

## Executed verification

Local PHP 8.4.24, Node 26.4.0, Vite 7.3.7. Global toolchains/Herd untouched. Node 22 CI remains a later, separately authorized gate.

| Command/check | Exit | Result |
|---|---:|---|
| npm.cmd run typecheck | 0 | PASS |
| npm.cmd run build | 0 | PASS; 3382 modules |
| php artisan test --compact --no-ansi | 0 | PASS; 30 tests, 684 assertions, DB disabled by phpunit.xml |
| git diff --check | 0 | PASS |
| Official rawConfigSchema.parse(components.json) | 0 | PASS; pinned Vue source, temporary zod 3.25.76 outside repo |
| Existing S0-06 baseline browser regression | 0 | PASS; 95 checks before implementation |
| node harness/evidence/ICH-UI-01B/browser.cjs | 0 | PASS; 196 final browser checks |
| Baseline/final Public/Auth computed-style deep equality | 0 | PASS |

The final application verification suite ran once after the last application edit. Later browser/evidence refinements changed no application code. No PHP changes required Pint; no Composer changes required dependency work.

## Browser evidence and limits

Chrome headless with a unique temporary profile; existing synthetic local presenter/workspace only. Baseline ran through http://iches-unisya.test. Repeated login attempts reached the existing five-per-15-minute demo limit during QA. Final QA used a temporary `CACHE_STORE=array php artisan serve --host=127.0.0.1 --port=8011 --no-reload` process with the existing local resources. No .env edit, cache clearing, migration, seeding, account creation or global service change. Server is stopped after QA. Authentication rate limiting was not tested under array cache; PHP security tests still passed. This is local browser evidence, not CI or production UAT.

Reproduction requires ICHES_BROWSER_QA=1, ICHES_QA_ORIGIN pointing to the local QA server, and ICHES_QA_DEMO_PASSWORD supplied privately in the process environment. The script does not log credentials/cookies/tokens. It closes the browser, logs out and deletes only its own unique temporary profile.

browser/browser-results.json records actual computed tokens, platform-rendered fonts, portal direction, locale/network/history assertions and results. Screenshots in browser/ cover Admin EN/ID/AR at 390/768/1440, dropdowns and sheets in all three locales, Auth EN/ID/AR at all widths, Public Foundation at all widths and Arabic drawer. baseline/ contains the actual pre-change Public/Auth screenshots. public-auth-comparison.json compares body/main/h1/input/button colors, fonts, sizes, borders and radii against the baseline; all sampled styles are identical. Public is the existing Foundation placeholder, not a completed CMS site.

Browser interactions use DOM focus/click and a dispatched Escape keyboard event. Native CDP key injection did not reach the page in this desktop session; no manual/native keyboard UAT is claimed. Actual focus trapping/trigger restoration and Escape handler behavior were asserted in the browser. Early harness attempts failed on minified CSS serialization and native key delivery; numeric color normalization and page event dispatch corrected the harness. No application keyboard/auth logic was changed to satisfy QA.

## Acceptance criteria

| ID | Status | Evidence |
|---|---|---|
| AC-01 | PASS | Verified machine/remote/clean main/base; direct-parent check at commit |
| AC-02 | PASS | components.json + official strict rawConfigSchema; pinned Neutral/Emerald reference |
| AC-03 | PASS | Alias-backed real imports; typecheck/build exit 0 |
| AC-04 | PASS | All 32 computed Admin tokens compared numerically to official reference |
| AC-05 | PASS | CSS font inspection + rendered JetBrains Mono, Geist Mono and Noto Sans Arabic |
| AC-06 | PASS | Before/after Public/Auth screenshots and identical computed styles |
| AC-07 | PASS | Actual body Dropdown/Sheet portals, fonts/tokens, EN/ID/AR directions and focus |
| AC-08 | PASS | EN/ID/AR same-document locale transitions, lang/dir, network assertions and history |
| AC-09 | PASS | Workspace persisted, actor retained, logout/guest protection; PHP negative tests |
| AC-10 | PASS | Admin/Auth all locales and Public at 390/768/1440; no horizontal overflow |
| AC-11 | PASS | Eleven application paths only; authorized append-only log/evidence exception |
| AC-12 | PASS | All mandatory commands exit 0 |

No unresolved implementation blocker. Remaining gates: independent ChatGPT review and owner acceptance, separately authorized push/PR, PHP 8.4/Node 22 CI, exact-head merge approval and post-merge sync. Stop after the single local commit.

All changed paths, including evidence, are listed in changed-paths.txt. No push, PR, merge, migration, seeding, deployment or next task performed.
