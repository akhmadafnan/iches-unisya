# ICH-S0-04 execution evidence — 2026-10-28

Authorization: owner GO CODING ICH-S0-04; branch feat/ich-s0-04-scoped-workspaces; parent 08753fa9dccdeef2a4c0fa4314f85d07b262d71e. Clean starting tree verified. One local commit only; no push/PR/merge/deployment.

Implemented server-derived assignment keys, Personal fallback, fresh active-grant validation on all authenticated routes in this slice, selected-role/event/institution Policies, unchanged self-only UserPolicy, CSRF-protected switch, minimal native accessible dashboard select. Auth user identity never changes. Supported scoped read keys: participant, presenter, reviewer, cohost_pic, superadmin; unknown roles and demo admin denied. Optional policy context preserves existing standalone resource-policy callers; HTTP guarded endpoints always supply validated selected context. Bank-settings ability is a scoped Superadmin-only policy stub, with no persistence or route.

Commands/results:
- Targeted: ICHES_DB_TESTS=1 php artisan test tests/Database/WorkspaceTest.php --display-warnings — exit 0, 3 PASS / 100 assertions.
- Final: php artisan test --display-warnings — exit 0, 12 PASS / 131 assertions; DB-free CI preserved.
- Final: ICHES_DB_TESTS=1 php artisan test tests/Database --exclude-group seed --filter '^(?!.*test_demo_seed).*' --display-warnings — exit 0, 21 PASS / 408 assertions. NOTE: PHPUnit matched class names despite the negative method filter; existing S0-03 demo-seeder test also ran inside its rollback transaction. No standalone seeder execution or persistent fixture changes.
- npm.cmd run typecheck — exit 0.
- npm.cmd run build — exit 0.
- php vendor/bin/pint --test — FAIL: 14 pre-existing unrelated files, primarily Windows CRLF; no mass formatting changes. Baseline had 16; the two affected files were normalized locally.
- php vendor/bin/pint --test app/Services/Workspaces.php app/Http/Controllers/WorkspaceController.php app/Http/Middleware/ValidateWorkspace.php app/Policies/EventPolicy.php app/Policies/InstitutionPolicy.php app/Http/Controllers/Auth/SessionController.php routes/web.php tests/Database/WorkspaceTest.php tests/Feature/WorkspaceSmokeTest.php — exit 0, PASS.
- git diff --check — exit 0.
- node <temporary browser.cjs> — exit 0; cached Playwright, compiled Vue dashboard with intercepted synthetic Inertia responses. Desktop 1440x1000, mobile 375x812, RTL, Tab focus and keyboard submission, no horizontal overflow or runtime JS errors. Screenshots attached. Native select option was chosen via Playwright selectOption; ArrowDown attempt did not commit the native selection in this automation environment. Not a live-session browser end-to-end test. Script copy: browser.cjs (uses existing local Playwright cache; no packages installed).

Security coverage: same-user multi-role switches; unchanged assignment rows; Personal denies event access; direct guest and CSRF denial; cross-event even with another eligible workspace; cross-institution; eventwide cannot grant institution scope; other-user key; forged/unknown/admin key; malformed input/session; loaded stale relations and revoked grant denied next HTTP request; ownership checks; bank stub rejects non-Superadmin, wrong event, institution-only Superadmin, Personal and revocation. Safe JSON only returns guarded resource ID/name; dashboard account props unchanged.

DB tests verify config and actual connection: MySQL iches_unisya at 127.0.0.1:3306, FK enabled. All fixture writes inside explicit transactions with teardown rollback. No migration/schema/reset/truncate, dependency, auth-flow, CI or governance edits.

Deferred: audited read-only Superadmin View as User needs its separately approved permissions/audit/display contract. No impersonation, support session, CMS, Finance or bank UI. No implementation blocker; whole-repo Pint remains baseline failure, remote CI and owner review pending.
