# 21K-10 — ICH-S0-03 REV-01 Independent Local Review / GitHub CI Rerun Gate

> 28 October 2026 · **REV-01 REVIEW_PASS_LOCAL / PENDING_OWNER_PUBLICATION / CI_NOT_RERUN**. This is a ChatGPT governance review only. It does not authorize push, PR update, merge, deployment, source modification, DB operations, or moving to S0-04.

## Verified exact local evidence
- Workspace: `D:\PINJAM-AFNAN\Herd\iches-unisya`, branch `feat/ich-s0-03-passwordless-auth`, local HEAD **`6b32e7580fb04fb789908a5f486d11d1bfa24adb`**.
- Parent **`6f7f9beb16e58eb7e0d5a32914331e8deb46e622`** (original PR #8 feature source), remote feature still at parent; working tree clean, branch ahead by one local commit.
- REV-01 changes exactly **3 paths**: `tests/Feature/AuthenticationSmokeTest.php` (+2 lines), append-only `harness/build-log.md` (+4 lines), new sanitized `harness/evidence/ich-s0-03/rev01.md` (+17 lines).
- Only functional diff: `$this->withServerVariables(['REMOTE_ADDR' => '192.0.2.77']);` at start of `test_endpoint_limits_are_independent_and_inertia_receives_a_readable_error`. Existing per-test 10 request loop, 429 and Inertia assertions unchanged. No production auth, app config, database, migration, seeder or CI YAML changes.
- Independently rerun from desktop (not Codex) `php artisan test --display-warnings`: **10 passed / 120 assertions**, exit 0; `php vendor/bin/pint --test`: **41 files PASS**. `git diff HEAD^ HEAD --check` returned no issues; worktree clean.
- Codex evidence `harness/evidence/ich-s0-03/rev01.md`: CI-like isolated **file-cache** test run `10 passed / 120 assertions`, targeted rate-limit `1 / 29`, standard `10 / 120`; that isolated file-cache run was **reviewed from evidence, not independently rerun**.
- Previous genuine GitHub [run 40706833873](https://github.com/akhmadafnan/iches-unisya/actions/runs/40706833873) was **FAIL** at `AuthenticationSmokeTest.php:80`, `9 passed / 1 failed`, with PHP Pint/Node22 stages skipped. No claim that the new commit passed remote CI.

## Audit disposition
**LOCAL REVIEW PASS** for this narrow test-isolation correction. Keep PR [#8](https://github.com/akhmadafnan/iches-unisya/pull/8) OPEN. Next, after a separate Owner **GO PUSH REV-01**, permit ordinary non-force push of exact local HEAD to the existing PR branch only; verify remote SHA and GitHub Actions Linux PHP8.4/Node22 **actual** results and exact PR diff before requesting merge GO. Do not run Codex again absent a new finding. No merge/deployment/database action authorized.

## Non-code creative assets supplied
Owner supplied `LOGO WEBP.zip` (not copied into repository), containing exactly five original transparent WebP files: `logo-iches.webp` (695x548), `logo-unisya.webp` (1378x432), `logo-unisya-bulat.webp` (934x934), `logo-forpim.webp` (1079x432), and `logo-kampus-peradaban.webp` (1138x268). Source zip inspected by ChatGPT and left unchanged; intended future work: verify official graphic identities and match the COMING SOON flyer, then later implement asset management in an authorized S1 frontend/CMS slice. Logo assets are not a GO for public site coding or content publication.
