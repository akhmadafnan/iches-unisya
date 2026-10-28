# ICH-S0-03 local implementation evidence — 28 October 2026

Owner GO: ICH-S0-03 only. Branch `feat/ich-s0-03-passwordless-auth`, parent `a05fc046df96fbea4b231f68c6301d6f13be9e0b` after clean fetch and fast-forward of main. No push/PR/merge/deploy or S0-04 work.

Implemented Laravel session guard, verified passwordless registration/login with six-digit HMAC-protected OTP and independent 32-byte random Magic Link, 10-minute expiry, five-attempt ceiling, single-use transactional row locking, 60-second durable cooldown and separate IP/email limits. Magic GET stores a digest in session and redirects to a token-free confirmation; POST redeems with CSRF. Session rotates at login; logout invalidates the old session and CSRF. Dashboard exposes only the current account name/email. Existing UUIDv7, scoped Policies, S0-02 migrations, lockfiles, CI and public Foundation source are unchanged.

## Executed local database actions

- Verified selected `iches_unisya`, configured `127.0.0.1:3306`, InnoDB, FK checks=1, batches 1/2; initial four domain tables empty, no unexpected tables/data.
- `php artisan migrate --pretend` exit 0: nullable `users.password`, new `auth_challenges`, indexes only. `php artisan migrate` exit 0: new `2026_10_28_000003_add_authentication_foundation`, batch 3. No prior migration edits or destructive rollback/reset.
- Explicit ignored local `.env` opt-in `ICHES_DEMO_MODE=true`, APP_ENV remains local; `php artisan db:seed --class=IchesLocalDemoSeeder` twice, exit 0 both times. No automatic seeding. Composer PSR-4 mapping added for standard Database\\Seeders namespace, no dependency or lockfile changes.
- Persistent synthetic accounts: `admin@iches.test`, `reviewer@iches.test`, `presenter@iches.test`, `participant@iches.test`. Passwords stored only as secure hashes. Names do not grant admin/reviewer powers. Only presenter/participant grants scoped to `iches-demo-local`; no global/bank/finance grants.
- Final inventory: users=4, events=1, institutions=0, role_assignments=2, auth_challenges=2 (both consumed by browser smoke), migrations=3. Auth/DB test fixtures rolled back. Tests preserve revoked role history and verify seed idempotency. No other database touched.

## Final checks

| Command | Exit | Actual result |
| --- | --- | --- |
| `composer validate --strict` | 0 | PASS |
| `php artisan test --display-warnings` | 0 | 10 tests, 120 assertions; original 4 Foundation smoke tests preserved; no DB connection |
| `$env:ICHES_DB_TESTS='1'; php artisan test tests/Database --display-warnings` | 0 | 18 tests, 308 assertions; original 8 DB tests preserved, transactional fixtures |
| `php vendor/bin/pint --test` | 0 | PASS |
| `npm.cmd run typecheck` | 0 | PASS |
| `npm.cmd run build` | 0 | PASS, Vite 7.3.7 |
| `php artisan migrate:status` / `php artisan route:list` | 0 | 3 applied migrations / 12 routes including Foundation and health |
| `git diff --cached --check` | 0 | PASS after evidence whitespace cleanup |
| Browser script using existing cached Playwright + Chromium on Herd | 0 | OTP/mail-log, Magic confirmation/replay, demo invalid/valid login, session rotation and old-cookie rejection, mobile overflow and keyboard, no browser runtime errors |

See `verification.txt` for sanitized final command output. Screenshots: login and dashboard at 1280x900 and 390x844. Manually visually inspected mobile login and desktop dashboard. Browser script reads OTP/link only from ignored local mail log, never prints them. Rerun only after 60-second email cooldown and within IP limits; it intentionally retains consumed synthetic challenge history.

Security coverage: invalid/expired/replayed OTP and Magic Link, fifth-failure lockout, resend invalidation/cooldown, generic responses, separate endpoint quotas and readable Inertia throttle errors, UUIDv7/no ordinary participant password or auto grants, production demo/seed/log-mail denial, host/IP gate, CSRF all mutations, session fixation/logout invalidation, cross-scope Policies, atomic failure rollback. Competing MySQL connection `FOR UPDATE NOWAIT` rejected with error 3572; replay tested separately. This verifies lock contention, not a parallel-browser load benchmark.

Execution notes: first targeted runs exposed test CSRF/session setup issues and missing seeder autoload; corrected before final PASS. Browser found shared numeric limiter keys; replaced with named endpoint limits and added regression coverage. Initial full Pint rejected baseline CRLF checkout bytes; locally normalized those files to LF, preserving their canonical Git content (none included in final diff). Windows optimized Composer dump stalled and was stopped; non-optimized autoload generation completed, manifest keeps existing optimize setting. No packages installed.

Local demo URL: `http://iches-unisya.test/dev/login`; passwordless URL: `http://iches-unisya.test/login`. Local verification used PHP 8.4.24 / Node 26.4.0. No remote Linux/Node22 CI, real SMTP delivery, production configuration UAT, Arabic translation/RTL UAT or load test was performed. Configure live mail and server access-log token redaction before any separately authorized production rollout. No local implementation blocker. S0-04 and PR #3 untouched.

## Changed files

- `.env.example`
- `app/Http/Controllers/Auth/SessionController.php`
- `app/Http/Middleware/AuthPrivacy.php`
- `app/Http/Middleware/LocalDemoOnly.php`
- `app/Http/Requests/Auth/AccessRequest.php`
- `app/Models/AuthChallenge.php`
- `app/Models/User.php`
- `app/Providers/AppServiceProvider.php`
- `app/Services/Auth/PasswordlessAuth.php`
- `composer.json`
- `config/auth.php`
- `config/iches-auth.php`
- `config/session.php`
- `database/migrations/2026_10_28_000003_add_authentication_foundation.php`
- `database/seeders/IchesLocalDemoSeeder.php`
- `harness/build-log.md`
- `harness/evidence/ich-s0-03/browser-smoke.cjs`
- `harness/evidence/ich-s0-03/dashboard-desktop.png`
- `harness/evidence/ich-s0-03/dashboard-mobile.png`
- `harness/evidence/ich-s0-03/handoff.md`
- `harness/evidence/ich-s0-03/login-desktop.png`
- `harness/evidence/ich-s0-03/login-mobile.png`
- `harness/evidence/ich-s0-03/verification.txt`
- `resources/css/app.css`
- `resources/js/components/AuthShell.vue`
- `resources/js/pages/auth/Demo.vue`
- `resources/js/pages/auth/Login.vue`
- `resources/js/pages/auth/Magic.vue`
- `resources/js/pages/dashboard/Index.vue`
- `routes/web.php`
- `tests/Database/AuthenticationTest.php`
- `tests/Feature/AuthenticationSmokeTest.php`
