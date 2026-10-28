# ICH-S0-03 REV-01 — 28 October 2026

Owner-authorized bounded correction on `feat/ich-s0-03-passwordless-auth`, parent `6f7f9beb16e58eb7e0d5a32914331e8deb46e622`. Work order read from `origin/main:docs/21K-09-S0-03-PR8-CI-REV01.md` after fetch because absent locally; no merge/rebase.

Only test-source change: set `REMOTE_ADDR=192.0.2.77` within the endpoint limiter test. All requests in that test retain one IP; the 10-request loop, 429 response and Inertia error assertions remain unchanged. No production auth, CI, migration, database, .env or committed PHPUnit configuration changes.

Executed results:

- `php artisan test --display-warnings`: exit 0, 10 tests / 120 assertions.
- `php artisan test --filter=test_endpoint_limits_are_independent_and_inertia_receives_a_readable_error --display-warnings`: exit 0, 1 test / 29 assertions.
- File-cache CI-like run: `php vendor/bin/phpunit --configuration <temporary-config> --display-warnings`: exit 0, 10 tests / 120 assertions, no warnings; 10 actual file-cache entries confirmed. Process-only APP_ENV=testing, CACHE_STORE=file, unique CACHE_PREFIX, DB_CONNECTION=disabled, SESSION_DRIVER=file, MAIL_MAILER=array. Temporary copy of phpunit.xml forced cache/session=file (repository XML forces array), with absolute test/bootstrap paths and LARAVEL_STORAGE_PATH pointing to new OS-temp storage. Temporary configuration deleted afterward. Existing local cache untouched; no database connection permitted by Feature mocks.
- `php vendor/bin/pint --test`: exit 0, PASS.
- `git diff --check` and staged diff check: exit 0, PASS.

Initial temporary-config invocation failed due XML path conversion/duplicate Artisan configuration handling; corrected by invoking PHPUnit directly with absolute paths. A subsequent run against existing local file cache found stale localhost counters in other tests. Final run used fresh isolated file storage, matching a clean CI checkout. File cache does not isolate those old entries merely through CACHE_PREFIX. No application limits were changed or assertions removed.

No frontend changes or database tests needed. Remote CI rerun remains pending publication; no push, PR update, merge or deployment performed.
