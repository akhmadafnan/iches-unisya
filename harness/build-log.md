# ICHES — Execution Evidence Log

> Initial snapshot: 28 October 2026. DRAFT documentation only; **no GO CODING, no code/tests executed**.

| Date | Task | Evidence | Status |
|---|---|---|---|
| 2026-10-28 | 20A Master Plan/Skill Registry/Harness draft | Documents prepared on `docs/19e-agent-workspace` in Draft PR #1; no application source or runtime tests | DRAFT_REVIEW / NO_GO |

**19F is the only current status board**. 20B allows append-only Codex entries here **only after separate named Task/Sprint GO** on feature branch. There is currently NO GO.
## 2026-10-28 — ICH-S0-01 — owner GO implementation evidence

- Authorization: explicit owner GO CODING ICH-S0-01 on 28 October 2026, bounded by work order 21B and execution contract 21D.
- Branch: feat/ich-s0-01-foundation; clean synced main base 4137dce48e85fab5c25c89b7f3a1fde1d0fa3b89; approved governance ancestor verified.
- Implemented: DB-independent Laravel 13.39.0, Inertia Laravel 2.0.28 / Vue adapter 2.3.29, Vue 3.5.44, TypeScript 5.9.3, Tailwind 4.3.3, Vite 7.3.7. Composer/npm lockfiles included.
- PASS (exit 0): locked Composer install, composer validate --strict, platform requirements, artisan version/route list, FoundationSmokeTest (3 tests / 22 assertions), Pint final check, npm install and npm ci, npm build, TypeScript typecheck, scoped diff/whitespace and credential-pattern checks.
- Herd: existing http://iches-unisya.test/ changed from baseline 404 to HTTP 200; unknown path HTTP 404; curl exits 0. No artisan server was used.
- Browser: Chrome desktop 1440x900 and mobile 390x844 mounted Vue, loaded Tailwind, correct title/task, no horizontal overflow or browser runtime/console/network/HTTP errors. Screenshots captured and visually inspected.
- Resolved failures: initial Pint import/line-ending findings fixed; Inertia navigation test fixture updated to send the current asset version after a post-build run returned protocol 409. Final smoke passes with built assets present. Failed-run evidence retained.
- Scope: protected governance/docs/Skills unchanged. No DB connection/schema/migration/seeder, authentication, CMS, other tasks, other Herd projects, push/PR/merge/deployment. Local environment and generated runtime assets ignored.
- Evidence: harness/evidence/ich-s0-01/handoff.md, changed-files.txt, verification.json, verification-before-test-fix.json, browser-smoke.mjs, desktop.png and mobile.png.
- Handoff: no implementation blocker remains; review and owner acceptance are pending separately. Existing documentation snapshots are for ChatGPT to update after review.

## 2026-10-28 — ICH-S0-07 — DB-free CI local implementation

Authorization: explicit owner GO CODING ICH-S0-07, Work Order 21I-02 / execution 21I-03. Branch feat/ich-s0-07-ci created from clean, fast-forwarded main at 42a8633245ad00a3eb809f03afded3f7d07955d5. Accepted feat/ich-s0-01-foundation remains 8eba7cb5b9da0577910beb919844165869591e05.

Changed paths: .github/workflows/ci.yml and this append-only log. No application, test, dependency/lockfile, governance or Skills content changes.

Workflow: pull_request to main and push to feat/**; shared branch concurrency cancels overlapping runs. One ubuntu-24.04 job, timeout 15 minutes, PHP 8.4 and Node 22, contents:read, checkout credentials not persisted. Three actions pinned to full SHAs verified against upstream tags. Composer install/npm ci consume existing locks. DB_CONNECTION=disabled; file cache/session, sync queue, array mail; no database service, migration, secret reference, environment file/key provisioning or deployment. PHP tests generate their own ephemeral key.

Actual local environment: Windows, PHP 8.4.24, Composer 2.10.1, Node 26.4.0, npm 11.17.0. Node 22 is configured in the Linux workflow but not installed or executed locally; no global tool/runtime settings changed.

| Actual command/check | Exit | Result |
|---|---:|---|
| git fetch origin main; git switch main; git pull --ff-only origin main | 0 each | Clean main safely synced; accepted PR #4 ancestry verified |
| composer validate --strict | 0 | PASS |
| composer install --no-interaction --prefer-dist --no-progress | -1 | Initial task-owned process interrupted after prolonged autoload stall; not a PASS |
| php -d auto_prepend_file= <existing Herd composer.phar> install --no-interaction --prefer-dist --no-progress | 0 | PASS from existing locked dependencies; reported Packagist DNS curl error 6 and used local cache |
| Same PHP/Composer command with process-only COMPOSER_DISABLE_NETWORK=1 | 0 | PASS, no dependency install/update/remove needed; autoload and package discovery completed |
| php artisan --version | 0 | Laravel Framework 13.39.0 |
| php artisan test | 0 | PASS, 3 tests / 22 assertions, including after final autoload generation |
| php vendor/bin/pint --test | 0 final | PASS; first run exited 1 for CRLF checkout line endings only |
| php vendor/bin/pint | 0 | Normalized local PHP line endings; git add --renormalize confirmed zero source/index diff |
| npm.cmd ci | 0 | PASS from package-lock.json, 106 packages; npm reported zero vulnerabilities |
| npm.cmd run typecheck | 0 | PASS |
| npm.cmd run build | 0 | PASS, Vite 7.3.7 |
| actionlint -shellcheck= -pyflakes= .github/workflows/ci.yml | 0 | PASS, actionlint 1.7.12; schema/expression checks, optional external shell/Python linters disabled |
| Static workflow trigger/required-command/SHA/negative-operation checks | 0 | PASS; no database services, secrets, dependency updates or deployment operations |
| git diff --check; git diff --cached --check | 0 | PASS |
| Protected governance/application/dependency diff and source index checks | 0 | PASS, unchanged content |

Validation tool was downloaded to a unique OS temporary directory from the official rhysd/actionlint v1.7.12 release, SHA256 verified against the release checksum list (6e7241b51e6817ea6a047693d8e6fed13b31819c9a0dd6c5a726e1592d22f6e9). No global installation or project dependency was added. Python YAML parser probe was unavailable (ModuleNotFoundError, exit 1); actionlint supplied the successful workflow validation instead. npm repeated its non-blocking esbuild postinstall approval warning; no approval settings changed, and actual build passed.

CI_NOT_RUN / PENDING_PUBLICATION: no GitHub Actions execution, check URL or Linux/Node 22 runtime PASS is claimed. Owner requested stop after local commit; no push, PR, merge, database operation or deployment performed. Review and publication require separate owner authorization. Transient local Packagist DNS was bypassed using already installed/cache dependencies; clean remote dependency download remains for the first authorized GitHub run.

## 2026-10-28 — ICH-S0-07 REV-01 — annotated tag pin correction

Owner authorized this bounded review fix on feat/ich-s0-07-ci. Review read from origin/main:docs/21I-04-ICH-S0-07-INDEPENDENT-CODE-REVIEW.md without merge/rebase. Existing commit 17e335049f209110e76a03dc99bc588cc7cee925 is preserved; this correction is a new follow-up commit.

Correction: only the setup-php action SHA changed, from d52fc211a0436f8839a587b3b5b8efddab4744ea (annotated tag object) to eb7c497e18156a6bbabfef1d3a82760b9eda3962 (official v2 target commit). The earlier assertion that all three pins were verified against upstream tags was incomplete: tag equality did not prove commit object type. Historical evidence above is retained unchanged. All other workflow configuration remains identical.

Independent object validation: GitHub Git API /git/ref/tags/v2 returned type tag and d52fc211a0436f8839a587b3b5b8efddab4744ea; /git/tags/d52fc211a0436f8839a587b3b5b8efddab4744ea returned type commit and eb7c497e18156a6bbabfef1d3a82760b9eda3962; /git/commits/eb7c497e18156a6bbabfef1d3a82760b9eda3962 confirmed that commit and its tree. The actions/checkout and actions/setup-node v6 refs were also checked through GitHub Git API: both remain type commit at their existing pinned SHAs. Assertion script exit 0.

| Actual verification command | Exit | Result |
|---|---:|---|
| git ls-remote https://github.com/shivammathur/setup-php.git refs/tags/v2 'refs/tags/v2^{}' | 0 | Tag object d52fc211...; peeled commit eb7c497e... |
| actionlint -shellcheck= -pyflakes= .github/workflows/ci.yml | 0 | PASS, existing temporary actionlint 1.7.12; optional external linters disabled |
| git diff --check | 0 | PASS |
| composer validate --strict | 0 | PASS |
| php artisan test | 0 | PASS, 3 tests / 22 assertions |
| php vendor/bin/pint --test | 0 | PASS |
| npm.cmd run typecheck | 0 | PASS |
| npm.cmd run build | 0 | PASS, Vite 7.3.7 |

Local runtime remains PHP 8.4.24 / Node 26.4.0. No dependencies reinstalled or upgraded. Scope limited to the one workflow line and this append-only correction; no app, lockfile, governance, Skills, database, deployment or secret changes. CI_NOT_RUN / PENDING_PUBLICATION: Linux/Node 22 and action execution still require a separately authorized publication and real GitHub run. No push, PR, merge, amend or rebase performed.

## 2026-10-28 — ICH-S0-07 REV-02 — Linux Inertia path and CI environment correction

Authorization: owner requested the bounded REV-02 correction on feat/ich-s0-07-ci, preserving c420d95175d7d25cd9fee6719180acaf0b684903 and earlier commits. Read origin/main:docs/21I-07-S0-07-LINUX-CI-FAILURE-REV02.md without merge/rebase. Read actual PR #5 job 122751236997 logs through the GitHub connector: https://github.com/akhmadafnan/iches-unisya/actions/runs/40669157978 . Existing Linux run FAILED at php artisan test (Foundation component missing, two truncated file_get_contents warnings); Node 22/frontend steps were skipped, not passed.

Root causes and bounded changes:
- Installed Inertia defaults inertia.testing.page_paths to resources/js/Pages, but the tracked Vue directory is resources/js/pages. Set the test-only config in FoundationSmokeTest::setUp to [resource_path('js/pages')]. Kept component('Foundation') and default ensure_pages_exist validation intact. Added a negative test that renders a missing component and expects the precise Inertia existence assertion failure. No application config or page behavior changed.
- Warning diagnosis disproved the manifest hypothesis. With the existing local env and the manifest temporarily unavailable, direct PHPUnit passed without warnings. With both .env and manifest unavailable, direct PHPUnit still passed, but php artisan test --display-warnings reproduced file_get_contents(.../.env) from vendor/vlucas/phpdotenv/src/Store/File/Reader.php:73. Artisan's reporting surfaced the suppressed missing-file warnings (on Windows all three original tests warned; the Linux failure masked the first test's warning). This was not a Vite manifest read.
- Added one CI preparation step immediately before tests: cp .env.example .env, creating a disposable runner-local, non-secret environment from the committed example. No key generation, database connection, build reordering, production credentials or tracked env file. Tests still generate ephemeral in-memory keys. Existing owner .env and built manifest were temporarily renamed only for isolated checks and restored in finally blocks; no contents were printed.

| Actual local command/check | Exit | Evidence |
|---|---:|---|
| php vendor/phpunit/phpunit/phpunit --display-warnings --fail-on-warning, before fix with manifest unavailable | 0 | 3 tests / 22 assertions; no warnings; disproves manifest-only explanation |
| Same direct PHPUnit command with both local .env and manifest unavailable | 0 | 3 tests / 22 assertions; no warnings |
| php artisan test --display-warnings --fail-on-warning, before fix without .env/manifest | 0 | Reported 3 file_get_contents(.env) warnings despite exit 0; not recorded as warning-free PASS |
| Diagnostic missing-env warning detector around php artisan test --display-warnings | 1 expected | Negative control positively detected the known .env warning; temporary files restored |
| php artisan test --display-warnings after fix, existing local env/assets | 0 | PASS, 4 tests / 36 assertions, including missing-component rejection |
| php artisan test --display-warnings after fix, manifest absent and disposable env copied from example | 0 | PASS, 4 tests / 36 assertions; output explicitly checked for zero warnings |
| php vendor/bin/pint --test | 0 | PASS |
| composer validate --strict | 0 | PASS |
| actionlint -shellcheck= -pyflakes= .github/workflows/ci.yml | 0 | PASS, existing temporary validator 1.7.12; optional external linters disabled |
| npm.cmd run typecheck | 0 | PASS |
| npm.cmd run build | 0 | PASS, Vite 7.3.7 |
| git diff --check and protected application/governance/lockfile diff audit | 0 | PASS |

Changed paths only: tests/Feature/FoundationSmokeTest.php, .github/workflows/ci.yml and this append-only build log. No source application, dependencies/lockfiles, auth, CMS, database/migration or governance changes. No vendor edits or warning suppression added. No dependencies reinstalled. No debug code remains.

Local verification ran on Windows/PHP 8.4.24/Node 26.4.0; available WSL executable did not provide a runnable Linux distribution. Linux case-sensitive behavior is grounded in the real failed GitHub job and tracked directory names, not claimed as locally rerun. NEW_COMMIT_CI_NOT_RUN / PENDING_OWNER_PUBLICATION: the published old SHA's CI remains failed; no corrected Linux/Node 22 PASS or CI URL is claimed. Stop after a new local commit, with no push, PR mutation, merge, deployment or database action.

## 2026-10-28 — ICH-S0-02 — MySQL identity/event foundation

Authorization: existing owner GO CODING ICH-S0-02, resumed after owner populated ignored local credentials; latest origin/main 21J-05 and selective UUID clarification followed. Branch feat/ich-s0-02-db-foundation safely fast-forwarded to 3ad72cfb99f4f5015203efb81f29563d49b6357c without reset/rebase or changing accepted feature branches. Stop after local commit; no publication authorization.

Read-only preflight authenticated from the local ignored .env: SELECT DATABASE() = iches_unisya, MySQL 9.7.1, default engine InnoDB, foreign_key_checks=1, zero initial tables. Only this schema was inspected. No credential value was printed or committed; existing owner .env was retained.

Implemented: environment-driven MySQL connection (strict/InnoDB/utf8mb4, UTC connection time, masked query bindings), sanitized .env.example with DB disabled by default, one additive identity/events migration, four models, narrowly scoped read Policies and synthetic transaction-based fixtures/tests. No login/password flow, CMS, finance, public design, other task or dependency update.

Tables: users, institutions, events, role_assignments, plus Laravel's migrations repository. UUIDv7 is generated by Laravel HasUuids only for users/institutions/events; all corresponding references are matching char(36) UUIDs. role_assignments uses a conventional BIGINT locator for Eloquent relationship-row updates, not an independently generated domain UUID; migrations uses Laravel's own key convention. role_key is a short string, year is an integer. Current assignments require an event; institution is optional for event-wide membership. Global role administration and module-specific grants remain for their authorized tasks.

Database constraints: unique normalized email generated from lower(trim(email)), unique event slug, actual UUID FKs with restricted parent deletion, and unique user/event/role/institution scope. Generated institution_scope normalizes NULL to an empty string so MySQL cannot allow duplicate event-wide assignments through NULL unique-key semantics. No official event record, time, speakers or other facts were seeded; starts_at/ends_at remain nullable.

Security: user reads are self-only; EventPolicy requires an active membership for that exact user/event; InstitutionPolicy additionally requires matching institution and explicit event context. No event-wide-to-institution privilege escalation, other-user access or stale cached grant after revocation; unspecified mutation abilities remain denied. RoleAssignment is guarded against request mass assignment. No HTTP endpoints for granting roles or administering entities were added.

| Actual command/check | Exit | Evidence |
|---|---:|---|
| git fetch origin main; git merge --ff-only origin/main | 0 each | Clean feature fast-forward, baseline 3ad72cfb99f4f5015203efb81f29563d49b6357c |
| Read-only PDO target/schema/engine/FK checks | 0 | Exact authorized DB, empty before migration |
| php artisan migrate --pretend | 0 | Reviewed additive domain DDL; Laravel also created its migrations repository during preparation |
| Pre-migration Laravel SELECT guard | 0 | Exact schema, only migrations repository present |
| php artisan migrate | 0 | Foundation migration applied, batch 1 |
| php artisan migrate (idempotence check) | 0 | Nothing to migrate |
| php artisan migrate:status | 0 | 2026_10_28_000001_create_identity_and_event_foundation: Ran, batch 1 |
| ICHES_DB_TESTS=1 php artisan test tests/Database --display-warnings | 0 | 7 passed / 95 assertions on actual MySQL; no skipped tests |
| php artisan test | 0 | Existing DB-free smoke: 4 passed / 36 assertions |
| php artisan test --display-warnings with local root .env temporarily replaced by sanitized .env.example | 0 | Same 4 smoke tests / 36 assertions, no DB credentials or database connection needed; original env restored in finally |
| php vendor/bin/pint --test | 0 final | PASS; initial check exited 1 only for CRLF in existing FoundationSmokeTest after Git fast-forward |
| php vendor/bin/pint; git add --renormalize -- tests/Feature/FoundationSmokeTest.php | 0 each | Local line-ending normalization; zero content diff for existing smoke test |
| composer validate --strict | 0 | PASS, manifests and both dependency lockfiles unchanged |
| npm.cmd run typecheck | 0 | PASS |
| npm.cmd run build | 0 | PASS, Vite 7.3.7 |
| actionlint -shellcheck= -pyflakes= .github/workflows/ci.yml | 0 final | PASS, 1.7.12; unchanged CI, optional external shell/Python linters disabled |
| curl.exe -sS -o NUL -w "%{http_code}" http://iches-unisya.test/ | 0 | HTTP 200 |
| curl.exe -sS -o NUL -w "%{http_code}" http://iches-unisya.test/s0-02-unknown-route | 0 | HTTP 404 |
| Final selected-schema table/engine/row-count/FK probe | 0 | All five tables InnoDB, FK checks 1; domain row counts all zero, migrations row count 1 |
| git diff --check; protected governance/CI/lockfile/old-smoke diff audit | 0 | PASS |

Database tests verify UUIDv7, matching FK types, relationship resolution, normalized email and event/role-scope uniqueness, missing-FK and restricted-delete errors, cross-user/event/institution denial, missing context denial, immediate revocation, and nested transaction rollback. Fixtures use synthetic names and example.invalid emails; all writes are inside transactions and were rolled back. Negative parent DELETE attempts were rejected by actual FKs; no real data was removed. No RefreshDatabase, schema rollback, destructive reset, wipe, DROP, TRUNCATE or foreign-key disabling was executed. Standard migration down() reversal code exists but was not invoked or authorized.

The previous temporary actionlint executable was no longer present; that initial command could not run (its stale shell LASTEXITCODE was not treated as a valid lint PASS). Re-downloaded the official 1.7.12 archive to a unique OS temp directory and verified SHA256 6e7241b51e6817ea6a047693d8e6fed13b31819c9a0dd6c5a726e1592d22f6e9 before successful lint execution. No global tools, other Herd sites or database accounts were changed.

DB integration tests reside outside phpunit.xml's default Feature suite and additionally require process-only ICHES_DB_TESTS=1, exact localhost/port/schema checks, and active foreign-key verification before fixtures. They never migrate/reset the schema themselves. Existing DB-disabled phpunit.xml and GitHub workflow are unchanged. No new GitHub run is claimed: local Windows/PHP 8.4.24/Node 26.4.0 checks PASS; Linux/Node 22 S0-02 CI awaits separate publication. No implementation blocker remains; owner review/publication/merge are separate gates.

## 2026-10-28 — ICH-S0-02 REV-03 — Active-only role assignment uniqueness

Authorization: owner requested the bounded REV-03 integrity correction under existing S0-02 GO. Read origin/main:docs/21K-01-S0-02-INDEPENDENT-REVIEW-REV03.md. Kept branch feat/ich-s0-02-db-foundation at reviewed parent 78bc6d9a904c86b334c28f645d908452295ba26e; no merge/rebase/amend or change to the first migration.

Before any migration: authenticated using the ignored local .env and verified SELECT DATABASE()=iches_unisya, MySQL 9.7.1, foreign_key_checks=1, exactly the original migration recorded, expected scope unique-index columns, and zero rows in users/institutions/events/role_assignments. Only the authorized schema was read; credentials were not printed.

Reproduced the defect before changing schema: newly added revoke/regrant regression test failed at creation of the new grant after revoke, MySQL 1062 from role_assignments_scope_unique (test exit 2). Its synthetic rows were rolled back, confirmed by a second pre-migration target/count guard.

Correction: new forward-only migration 2026_10_28_000002_make_role_assignment_uniqueness_active_only modifies only the existing stored generated institution_scope expression to CASE WHEN revoked_at IS NULL THEN COALESCE(institution_id, '') ELSE NULL END. Retains the existing unique index (user_id,event_id,role_key,institution_scope), existing rows, UUID FKs, numeric assignment key, models and policies. Active event-wide grants use non-NULL empty scope, active institutional grants use institution UUID, and revoked grants use NULL, so only historic rows can repeat the scope. No new column/index or destructive table/reset operation is necessary. down() refuses reversal because restoring the former uniqueness rule can conflict with preserved revoked history; no schema rollback was executed.

Regression covers both NULL institution and specific institution: active duplicate rejection, two revoke/new-grant cycles, distinct retained records, unchanged revocation timestamps, exactly one active row and two revoked rows per scope, duplicate active insert rejection, rejection of reactivating an old row while another grant is active, and cross-user/event/institution denial. Existing uniqueness/FK/UUID/policy/revocation/transaction tests remain intact.

| Actual command/check | Exit | Result |
|---|---:|---|
| Read-only PDO/Laravel DB target, original migration/index and row-count preflight | 0 | PASS, authorized schema and expected original state |
| ICHES_DB_TESTS=1 php artisan test tests/Database --filter=test_regrant_keeps_revocation_history_and_rejects_duplicate_active_scopes (before correction) | 2 expected | RED, reproduced MySQL 1062 at regrant |
| Pre-migration target/FK/row-count guard after failed regression | 0 | PASS, no persistent fixture rows |
| php artisan migrate --pretend | 0 | Exactly one ALTER TABLE MODIFY generated-column expression, no reset/drop or DML |
| php artisan migrate | 0 | New correction applied as batch 2 |
| php artisan migrate:status | 0 | Original migration Ran batch 1; new correction Ran batch 2 |
| ICHES_DB_TESTS=1 php artisan test tests/Database --display-warnings | 0 | GREEN, 8 tests / 134 assertions, none skipped |
| php artisan test --display-warnings | 0 | Existing DB-free smoke 4 tests / 36 assertions |
| php vendor/bin/pint --test | 0 | PASS |
| composer validate --strict | 0 | PASS |
| npm.cmd run typecheck | 0 | PASS |
| npm.cmd run build | 0 | PASS, Vite 7.3.7 |
| Final selected-schema generated-column/index/FK/row-count audit | 0 | PASS: nullable STORED GENERATED active-only expression, same four-column unique index, FK checks on, four domain tables empty, migrations=2 |
| git diff --check and original migration/protected app/CI/governance/dependency audit | 0 | PASS |

Schema mechanism cross-checked against MySQL's official generated-column ALTER documentation: https://dev.mysql.com/doc/refman/8.4/en/alter-table-generated-columns.html ; actual compatibility proved by migration and tests on local MySQL 9.7.1. No other database, production account/data, credentials, runtime application behavior, routes, dependency/lockfile, CI workflow or governance file changed. Previous build-log evidence preserved append-only.

Local Windows/PHP 8.4.24/Node 26.4.0 verification only; corrected feature SHA has no GitHub/Linux/Node22 CI run yet. No remaining local integrity failure. Stop after a new local correction commit, without push, PR, merge, database reset or deployment.

## 2026-10-28 — ICH-S0-03 authentication foundation (owner GO, local commit only)

Branch: feat/ich-s0-03-passwordless-auth, parent a05fc046df96fbea4b231f68c6301d6f13be9e0b. Implemented session authentication, passwordless Email OTP/Magic Link, minimal login/dashboard/logout, guarded local demo login and explicit idempotent synthetic seeder. UUIDv7, scoped Policies, previous migrations and DB-free CI preserved. No dependency added.

Verified exact owned local iches_unisya / 127.0.0.1:3306 / InnoDB / FK on / batches 1-2 and empty initial domain tables before writes. Additive migration 000003 applied as batch 3 after pretend review. Explicit local demo seeder executed twice with opt-in; final persistent inventory users=4, events=1, institutions=0, role_assignments=2, consumed auth_challenges=2, migrations=3. All temporary test fixtures rolled back; no resets or other database writes.

Final executed checks: composer validate --strict PASS (exit 0); php artisan test --display-warnings PASS 10 tests/120 assertions (exit 0); ICHES_DB_TESTS=1 php artisan test tests/Database --display-warnings PASS 18 tests/308 assertions (exit 0); php vendor/bin/pint --test PASS (exit 0); npm.cmd run typecheck/build PASS (exit 0); migration status/routes PASS (exit 0). Herd Chromium browser smoke PASS (exit 0): local mail-log OTP, one-use Magic Link, demo validation/login, regenerated session/logout and rejected old cookie, desktop/mobile/keyboard, no runtime JS errors. Baseline Windows CRLF normalized locally without canonical source changes; prior migrations remain outside diff.

Sanitized command output, security coverage, screenshots, browser script and limits: harness/evidence/ich-s0-03/. Local only; no real SMTP/production UAT or remote CI claimed. Separate named endpoint quotas fixed a browser-discovered limiter collision. No local blocker. Stop after local commit before push, PR, merge, deployment or next task. S0-04/PR #3 untouched.

## 2026-10-28 — ICH-S0-03 REV-01: isolate limiter test IP

Owner-authorized test-only fix on feat/ich-s0-03-passwordless-auth, preserving parent 6f7f9beb. Added REMOTE_ADDR=192.0.2.77 only within the limiter test; kept 10 requests, 429 and readable Inertia response assertions. No production auth/CI/config/migration/database changes. Standard DB-free suite PASS 10/120, targeted limiter PASS 1/29, actual isolated file-cache/session CI-like suite PASS 10/120 with no warnings (all exit 0); Pint and diff checks PASS (exit 0). Temporary PHPUnit configuration/storage used because repository test XML forces array and existing local file counters persisted; existing cache untouched. Evidence: harness/evidence/ich-s0-03/rev01.md. Local follow-up commit only; remote CI pending, stop before push/PR update/merge/deploy.
