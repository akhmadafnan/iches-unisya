# ICH-S0-06 local handoff — 2026-10-29

Owner's existing named GO CODING remains valid. Resumed the retained partial source on DESKTOP-PE5D86C, branch `feat/ich-s0-06-backoffice-shell`, direct parent/base `9ac325422ddd7550cacfb4c14455c9228f059945`. This record supersedes the incomplete state in `blocked-local-execution.md`; that earlier record is retained as historical evidence.

## Implemented scope

- Original responsive workspace layout: collapsible desktop sidebar, topbar, breadcrumb, skip link, real account menu/logout, and mobile sheet using Reka's modal Dialog primitives (focus trap, Escape, overlay dismissal, focus restoration, scroll lock). Desktop breakpoint closes an open mobile sheet. Logical start/end CSS and inherited document direction support RTL; directional icons rotate selectively.
- Manual shadcn-vue-compatible Vue3/Tailwind4 components: Primitive/CVA/clsx/tailwind-merge Button and Reka-backed SheetContent. No React/template preset or global CLI installation. Sidebar links only to the actual dashboard; other business modules are explicitly described as unavailable.
- Typed Vue I18n dictionaries for English, Indonesian and Arabic across the shell/dashboard and existing OTP, magic-confirmation and local demo pages. Default/fallback English; local Inter, Sora and Noto Sans Arabic fonts, ivory/forest/near-black/gold theme. Server-provided role/event/institution fields generate translated workspace labels without using labels as authority.
- Allowlisted CSRF-protected `POST /locale` persists only `en`, `id`, `ar` in session, uses fixed guest/authenticated destinations and leaves identity/workspace/grants unchanged. Request middleware sets locale before rendering; initial Blade html has lang/dir; client initialization and Inertia navigation synchronize document lang/dir and Vue I18n.
- Server-owned workspace choice keys, validation, active-grant checks and Personal fallback are preserved. Additive choice fields expose only role plus event/institution display names; no private files, assignment internals or account secrets are shared. No grant-writing/impersonation or synthetic CMS/Finance/Paper navigation.

## Final checks

| Command/check | Exit/status | Evidence |
| --- | --- | --- |
| `php artisan test --compact --no-ansi` | 0 / PASS | Serial DB-free suite: **30 tests, 684 assertions**, including eight new shell Feature tests. |
| `composer validate --strict` | 0 / PASS | Manifest valid. |
| `vendor/bin/pint --test app/Http/Controllers/LocaleController.php app/Http/Middleware/SetLocale.php app/Http/Middleware/HandleInertiaRequests.php app/Services/Workspaces.php bootstrap/app.php routes/web.php tests/Feature/WorkspaceShellTest.php` | 0 / PASS | Seven task PHP paths; formatter applied only to these paths. |
| `npm.cmd run typecheck` | 0 / PASS | Executed inside Codex workspace-write sandbox after resume. |
| `git diff --check` | 0 / PASS | Repeated at final handoff/staging. |
| `npm.cmd run build` | **EXTERNAL PASS, exit 0 reported by ChatGPT** | Owner's resume message reports ChatGPT's independently authorized normal remote terminal validation on the same current local worktree, Vite **7.3.7**, **3.95 sec**. This was **outside Codex sandbox**. Codex did not rerun the build after resume or bypass the sandbox. No frontend/dependency/Blade files changed during resume; only PHP formatting, tests and evidence changed. |
| Browser QA | **BLOCKED_BROWSER_QA** | No browser tool exposed. No browser run, visual inspection, keyboard UAT, screenshots or responsive/Arabic browser PASS is claimed. |
| Path and secret-pattern review | 0 / PASS | Only allowed application/dependency/test paths and append-only harness evidence; no migrations, governance, .env or sensitive paths. Targeted private-key/GitHub/AWS/OpenAI-token patterns found no hits; code diff manually reviewed. |
| Lockfile review | PASS | New direct packages use exact versions; all resolved package URLs are the npm registry. Lifecycle scripts were disabled during dependency installation. |

The initial new-test run exposed fixture issues (Inertia asset-version header and a closure capturing a mock query key by value); both were corrected in the test file. Scoped Pint corrected line endings/operator spacing. Final full suite is green; no application code failures remain known from executed checks.

## Test boundaries

New HTTP Feature tests exercise guest/authenticated EN/ID/AR session round trips, initial Blade lang/dir, real Inertia JSON locale/direction/supported list, invalid locale values/types (422), invalid session fallback, CSRF (419), fixed redirects, account privacy, no implicit admin grant by email, locale mutations preserving identity/workspace, five actual allowed role types in server choice results, forged/foreign workspace-key rejection (403), malformed payload validation, stale-grant Personal fallback and logout.

`DB::connection` is forbidden by a mock expectation, PHPUnit uses the disabled DB connection and array sessions/cache, and no model is saved. Workspace relation/query results are mocked at the persistence boundary. Real Workspaces selection/current/choice logic, route/controller/middleware and auth/session behavior run. These tests do **not** establish real MySQL grant filtering, cross-event/institution SQL isolation, end-to-end OTP delivery or browser behavior. Existing unchanged DB-free auth/private-file tests also remain green. No opt-in DB suite, migration, seeding, reset or live-data read/write was performed.

## Remaining limitations and next boundary

Browser responsive/keyboard/RTL UAT remains blocked. External build provenance above is reported honestly; Codex's earlier sandbox `spawn EPERM` remains an environment limitation. Remote PHP8.4/Node22 CI was not run. Product review/owner acceptance remain separate from this local implementation handoff. Stop after exactly one local feature commit; no push, PR, merge, deployment or next-slice work.

## Exact changed paths

```text
app/Http/Controllers/LocaleController.php
app/Http/Middleware/HandleInertiaRequests.php
app/Http/Middleware/SetLocale.php
app/Services/Workspaces.php
bootstrap/app.php
routes/web.php
package.json
package-lock.json
resources/css/app.css
resources/js/app.ts
resources/js/types.ts
resources/js/components/AuthShell.vue
resources/js/components/navigation/AppSidebar.vue
resources/js/components/navigation/AppTopbar.vue
resources/js/components/navigation/LocaleSwitcher.vue
resources/js/components/navigation/MobileNavigation.vue
resources/js/components/navigation/WorkspaceSwitcher.vue
resources/js/components/ui/Button.vue
resources/js/components/ui/SheetContent.vue
resources/js/i18n/index.ts
resources/js/i18n/locales/en.ts
resources/js/i18n/locales/id.ts
resources/js/i18n/locales/ar.ts
resources/js/layouts/WorkspaceLayout.vue
resources/js/pages/auth/Demo.vue
resources/js/pages/auth/Login.vue
resources/js/pages/auth/Magic.vue
resources/js/pages/dashboard/Index.vue
resources/views/app.blade.php
tests/Feature/WorkspaceShellTest.php
harness/build-log.md
harness/evidence/ICH-S0-06/blocked-local-execution.md
harness/evidence/ICH-S0-06/local-verification.md
```

## Final local commit blocker

The authorized bounded `git add -- <task paths>` failed with exit 1: `fatal: Unable to create .../.git/index.lock: Permission denied`. The current environment grants read access to repository Git metadata; no policy, ACL, lock file or sandbox changes were attempted. No alternative Git metadata path or forced write was used. Commit creation therefore could not proceed. This is an environment restriction, not a failing application test or a request for a new coding GO.

Final read-only verification: branch feat/ich-s0-06-backoffice-shell; HEAD remains 9ac325422ddd7550cacfb4c14455c9228f059945; zero commits above the base; index has no staged changes; source/tests/evidence remain modified/untracked. Worktree is NOT clean. Exactly zero local commits were created, so no new SHA/direct parent can be reported. The intended single commit's base remains the SHA above. Normal Git index/object/ref writes must be available within the authorized workspace sandbox before the requested one-commit handoff can finish. Source and tests are retained; do not discard/restart them.
