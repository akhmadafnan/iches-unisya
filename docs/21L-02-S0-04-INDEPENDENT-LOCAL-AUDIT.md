# 21L-02 — ICH-S0-04 Independent Local Audit (review PASS, remote CI pending)

> **28 October 2026 — ChatGPT independent review of LOCAL Codex commit, not a push/merge authorization.** Status: **REVIEW_PASS_LOCAL / PENDING_OWNER_GO_PUSH_PR / REMOTE_CI_NOT_RUN**. Owner established Git governance: Codex VS Code alone authors application code, ChatGPT reviews, and every publication/merge is separately authorized. No source changes or persistent DB/schema changes by ChatGPT.

## Immutable local commit facts

- Local workspace: `D:\PINJAM-AFNAN\Herd\iches-unisya`, branch `feat/ich-s0-04-scoped-workspaces`, clean working tree.
- Verified feature SHA: **`7edd837eb7285b3dbed0dbeacc2eb5546d76e960`**; actual parent verified by `git rev-list --parents -n 1 HEAD`: **`08753fa9dccdeef2a4c0fa4314f85d07b262d71e`** (S0-04 owner-GO work-order baseline). The first CMD invocation of `git rev-parse HEAD^` echoed HEAD due Windows shell caret escaping; authoritative parent from `rev-list` above, with no source changes.
- Exactly **16 changed paths**: `app/Services/Workspaces.php`, `app/Http/Controllers/WorkspaceController.php`, `app/Http/Middleware/ValidateWorkspace.php`, `app/Policies/EventPolicy.php`, `app/Policies/InstitutionPolicy.php`, `app/Http/Controllers/Auth/SessionController.php`, `routes/web.php`, `resources/js/pages/dashboard/Index.vue`, `tests/Database/WorkspaceTest.php`, `tests/Feature/WorkspaceSmokeTest.php`, `harness/build-log.md`, `harness/evidence/ICH-S0-04/results.md`, `browser.cjs`, `desktop.png`, `mobile.png`, `mobile-rtl.png`.
- Diff: **382 insertions / 9 deletions**, no migrations, seeders, CI pipeline, package lock or other unrelated production modules.
- Source review confirms server-derived active assignment workspaces with allow-listed role keys and `personal` fallback after revoked/malformed session context; the authenticated principal does not change on switch. Guards enforce selected event/institution active grant, reject other-user/unknown/demo-admin roles, and expose only scoped ID/name in test API endpoints. Bank Settings only has a deny-by-default Superadmin/eventwide policy stub; no bank settings route, data, Finance or mutations implemented.
- A future module calling `EventPolicy::view`/`InstitutionPolicy::view` must supply the intended validated active workspace when context isolation is required; current HTTP demo endpoints do so. Do not generalize the stub into a global bank permission without a separately reviewed security contract.

## Independent verification (actually rerun by ChatGPT on owner Windows desktop)

| Check | Observed result |
|---|---|
| `php artisan test --display-warnings` | **12 tests / 131 assertions PASS**, exit 0 |
| `ICHES_DB_TESTS=1 php artisan test tests/Database/WorkspaceTest.php --display-warnings` | **3 tests / 100 assertions PASS**, exit 0 |
| `ICHES_DB_TESTS=1 php artisan test tests/Database --display-warnings` | **21 tests / 408 assertions PASS**, exit 0; includes original S0-03 auth and foundation regressions plus workspace cases |
| `composer validate --strict` | PASS, exit 0 |
| `php vendor/bin/pint --test` limited to 9 task-relevant PHP files | **9 files PASS**, exit 0 |
| `npm.cmd run typecheck` | PASS, exit 0 |
| `npm.cmd run build` | PASS, exit 0, Vite 7.3.7 |
| `git diff HEAD~1 HEAD --check`, clean worktree | PASS |
| `php vendor/bin/pint --test` entire repository | **FAIL — 14 baseline issues in unrelated original PHP files**; mostly Windows CRLF checkout / previous formatting from S0-03. Existing Linux GitHub CI for S0-03 had passed before this branch. **Do NOT claim whole-repo Pint PASS** until verified in new Linux CI. No mass cleanup included in S0-04. |

Database tests enforce the owned **`iches_unisya`** localhost:3306 schema with FK checks on; all synthetic fixture and demo-seeder test writes are enclosed in explicit transactions with rollback. **No migration, refresh/fresh, schema reset, persistent seeding or deployment performed during this audit**.

## Review of Codex browser evidence (not independently browser-rerun)

- Local evidence file `harness/evidence/ICH-S0-04/results.md` is on the feature branch only, not yet published on GitHub; it includes compiled dashboard fixture desktop 1440 × 1000, mobile 375 × 812, RTL, native select focus and keyboard POST. Browser interactions intercepted synthetic Inertia JSON. Codex clearly disclosed that ArrowDown did not perform native selection in its automation environment, and no real authenticated browser/backend session was exercised.
- HTTP backend isolation was separately verified by the actual MySQL transaction-based feature tests; **live end-to-end browser verification remains pending**, and no remote GitHub Actions run exists for the feature SHA.

## Decision and next gate

**REVIEW_PASS_LOCAL** for the S0-04 scoped vertical slice. No critical defect found in the reviewed code or independently run tests. Preserve original production authentication, brand colors/fonts, no full impersonation, no CMS redesign. The narrow outstanding gates are remote Linux PHP 8.4/Node 22 CI and optional live-session browser validation before final merge acceptance; the 14 unrelated Windows Pint findings must remain transparent. 

**STOP before push, PR, merge, new migration, reset or deployment.** After explicit owner **GO PUSH & PR ICH-S0-04**, normal push of EXACT commit `7edd837eb7285b3dbed0dbeacc2eb5546d76e960`, create a PR to main, independently inspect GitHub CI and base/mergeability; no Codex token required unless a genuine CI code defect appears.
