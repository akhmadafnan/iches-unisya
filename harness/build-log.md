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
