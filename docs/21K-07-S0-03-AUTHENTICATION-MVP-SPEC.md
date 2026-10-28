# 21K-07 — ICH-S0-03 Authentication MVP: Architecture & Acceptance Contract

> **28 October 2026 · IMPLEMENTATION SPEC READY / NOT CODE GO.** Owner instruction: “silahkan lanjutkan pekerjaan ini secara konkrit dan komprehensif”. The Owner wants concrete preparation and simplified dev workflow, while repository governance [20B](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md) explicitly says “lanjut” is **NOT** a named coding GO. This document scopes the next coding slice; Codex edits only after owner says **`GO CODING ICH-S0-03`**. No app code, SQL or migration was executed by ChatGPT.

## Verified baseline and constraints

- [PR #6](https://github.com/akhmadafnan/iches-unisya/pull/6) merged S0-02. Current app stack Laravel 13/PHP8.4, Inertia Vue3/TS, MySQL/InnoDB `iches_unisya`; S0-01/S0-02/S0-07 COMPLETE. Existing `users` UUIDv7, `email`, generated unique `normalized_email`, nullable `email_verified_at`, **no password column**. Existing role assignments have event/institution scope, numeric assignment ID and audit-preserving revoke/regrant uniqueness.
- `routes/web.php` only serves the public Foundation page at `/`; `config/auth.php` **does not exist**; `config/session.php` and `config/mail.php` do exist. Laravel app currently has no login/OTP/session auth controllers or auth UI. Existing DB-free GitHub CI: PHP 8.4 / Node22, 4 smoke tests/36 assertions. MySQL tests are **local opt-in only**, with transaction rollback.
- [RD-06 and NEW-003](09-KEPUTUSAN-DAN-ISU-TERBUKA.md): participants login via **Email OTP / Magic Link, without password**. [21K-06 owner fixture policy](21K-06-DEV-DEMO-USER-SEED-POLICY.md): dev seeded emails `@iches.test`, uniform demo password `password` when password login exists, hashed at rest. This is **not** a general password requirement or production credential.
- Canonical workspace `D:\PINJAM-AFNAN\Herd\iches-unisya`. Read-only desktop preflight showed **clean old `feat/ich-s0-02-db-foundation` at `f4eb25c...`**, local `origin/main` stale; Codex must fetch and safely switch/fast-forward `main` before **new** S0-03 feature branch.

## Agreed small vertical MVP

| Feature | Implementation target | Excludes |
|---|---|---|
| Browser session guard | Create Laravel `config/auth.php` with Eloquent User provider and `web` guard; session regeneration on success, invalidate + CSRF regenerate at logout, route auth middleware | OAuth / social SSO / multi-guard system |
| Passwordless attendee onboarding | Request access with email and optional name; send short-lived code and opaque one-time Magic Link via Laravel mail; only create a new verified user **after proof of mailbox control**, preserving normalized email uniqueness | Password requirement for ordinary participants; real participant records in seed |
| OTP verification | Six-digit random OTP, hashed/HMAC protected at rest, TTL **10 min** default, at most **5 tries** per challenge, consume once atomically; expired/replayed/invalid codes rejected | Fixed OTP, secrets in responses, tokens persisted plaintext |
| Magic Link | Independent cryptographically random token (at least 32 random bytes), store hash only, one-time and TTL **10 min**; GET may show non-sensitive confirmation but must **not consume** link due to email scanners; user POST confirms with CSRF, then session login | Link token in logs outside intended local mail log; automatic login on prefetch |
| Email delivery | `MAIL_MAILER=log` for local `@iches.test` and normal Mail/Notification for future configured provider; generic response to avoid user enumeration | Mandatory live SMTP or external provider for MVP |
| Demo login | `admin@iches.test`, `reviewer@iches.test`, `presenter@iches.test`, `participant@iches.test`, all shared **`password`** and saved using `Hash::make`; route/login activated only when **`APP_ENV=local` AND `ICHES_DEMO_MODE=true`**; never available in production | Shared superadmin credentials in live application; production password login |
| Minimal authenticated page | `/dashboard` with signed-in identity + logout, no backoffice privilege or sensitive data leak; keep existing `/` and Foundation smoke | Full backoffice, CMS, Switch Workspace, paper/finance/QR |
| Minimal role seeding | One clearly synthetic event + optional synthetic institution, event-scoped role grants only when actual approved role keys exist. Repeat seeding idempotently; do not resurrect revoked assignments automatically | True bank/superadmin/finance powers, deleting real DB records |
| Auth challenge persistence | One narrowly scoped `auth_challenges` or equivalent table, with BIGINT or UUIDv7 PK according to lifecycle; nullable user UUID FK if needed, normalized email, hashes, expiry, attempts, consumed flag and indexes; **no plaintext OTP/token** | External Redis dependency or many speculative auth tables |
| Optional demo password storage | Add **nullable** `users.password` using **new forward additive migration**, and only fill for demo users. Keep ordinary passwordless users `NULL`. | Rewriting existing two migrations; forced passwords for attendees |

## Authentication request/response flow

1. User opens `GET /login`, submits normalized email (`POST /auth/request`). Response has generic “If permitted, check your email” wording for both known and unknown users. For a new participant, capture display name when needed, then **do not create user until OTP or link verifies**.
2. Server validates and limits request by email + IP and cooldown; issues **one current** challenge per email, with random six-digit OTP and one-time Magic Link, storing only hashes. Uses Laravel Mail without external dependency in local. Do not return OTP, raw token, original email list, secrets or identifiable User objects in Inertia props.
3. User enters code at `POST /auth/verify-code` or opens opaque link confirmation `GET /auth/magic/{token}` and confirms `POST /auth/magic/{token}`. Check expiry, attempts and single-use inside DB transaction/row lock to prevent concurrent double redemption. Consume challenge before establishing authenticated session.
4. On verified challenge, resolve existing normalized user or create exactly one user with valid name + email, mark `email_verified_at`, call `Auth::login($user)` and `request()->session()->regenerate()`, redirect to guarded `/dashboard`. No role elevation from email domain. Login requests don't silently assign additional roles.
5. `POST /logout` under auth/CSRF performs `Auth::logout()`, invalidates session, regenerates CSRF token and redirects safely. All protected pages deny guest access; no open redirect `return_to` or untrusted host in Magic Link.
6. **Development-only alternate login**: `GET /dev/login` and `POST /dev/login` (or clearly named equivalent), available **only** with local environment + opt-in flag + local host. Uses Laravel password hash verification and standard session login. Role names alone are not authorization. Require rate limiting even in local mode. Log no plaintext passwords or OTP tokens.
7. Logout/login error displays should be understandable to nontechnical users. Preserve `Foundation` public landing page. Use existing UI stack/colors; avoid implementing the whole conference CMS now.

## Practical defaults (S0-03 implementation, tunable later)

- OTP: random 6-digit numeric, **10-minute expiry**, maximum **5 verification attempts** per challenge. Send cooldown **60 seconds** per normalized email. Request limiter recommended **5 per 15 minutes per email** plus IP-level limiter. Do not expose whether an email exists; failures count and eventually lock out challenge. Use cryptographically secure randomness, constant-time hash comparison.
- Magic Link: >=32 random bytes encoded URL-safe, stored hashed, one use within **10 min**; any new challenge invalidates previous outstanding challenge for that email. Avoid secret tokens in general access logs and frontend props; do not put raw tokens in persistent application logs other than consciously enabled **local mailer log**.
- Trusted localhost: in local default `MAIL_MAILER=log` writes OTP and link into **local-only** Laravel log for manual use (per Laravel guidance). When test suite runs, use Mail::fake() or array driver. Local `.test` recipients do not deliver real external mail.
- Session: HttpOnly + SameSite=Lax and safe cookie settings; enable Secure on HTTPS production, with predictable behavior for Herd local HTTP. CSRF on all web mutations, login session regenerate, logout invalidate. No production APP_DEBUG or weak demo mode.
- UUIDv7 remains for canonical entities, matching FKs; temporary authentication challenges may use numeric PK, with *opaque high-entropy token* rather than reliance on guessing UUID.
- Preserve DB-free CI and its current tests; **new DB-backed auth HTTP tests only opt-in** with strict `ICHES_DB_TESTS=1` and exact schema/host checks, transactional rollback. No `RefreshDatabase`, `migrate:fresh`, reset, global seed on main dev DB.

## Seeder contract and safe production cutover

- Suggested explicit command `php artisan db:seed --class=IchesLocalDemoSeeder` under local-only + `ICHES_DEMO_MODE=true`. The exact seeder class may vary; no global auto-seeding. Ensure `updateOrCreate`/idempotent normalized-email logic and stable event slug like `iches-demo-local`; all records clearly synthetic, no fabricated actual 2027 schedule.
- Demo users: `admin@iches.test`, `reviewer@iches.test`, `presenter@iches.test`, `participant@iches.test`; all password hash corresponds to **`password`**, never plain DB text. Design role assignment idempotently with existing revoke history semantics; don't auto-regrant previously revoked role.
- Fail closed for any demo login/seed in staging/production even when flag accidentally set; development-only email suffix is not allowed through production demo pathway.
- Prior to production: set `ICHES_DEMO_MODE=false`, verify no demo routes/seeder reachable, no `@iches.test` users in live database, and no default `password` authenticates. Real email delivery/auth secrets/cookie policies and user privacy/security UAT must pass. Do not wipe development database under the present task.

## Required UAT/acceptance examples

| Case | Expected |
|---|---|
| AUTH-01 | Guest can see login page, but protected dashboard redirects/denies |
| AUTH-02 | OTP issued via local mail log without external SMTP |
| AUTH-03 | Valid OTP verifies email once and logs in with regenerated session |
| AUTH-04 | Expired, wrong or 6th OTP attempt rejected |
| AUTH-05 | Reused OTP rejected, even with concurrent requests |
| AUTH-06 | Valid Magic Link GET displays confirmation **without consuming**; POST succeeds only once |
| AUTH-07 | Tampered/expired Magic Link and replay rejected |
| AUTH-08 | Anonymous request response doesn't reveal whether email exists |
| AUTH-09 | Resend cooldown and per-IP/email throttle enforced |
| AUTH-10 | New verified user created once, UUIDv7, normalized unique email; no auto event/finance privileges |
| AUTH-11 | Session fixation prevention; logout invalidates session/CSRF and old session denied |
| AUTH-12 | Cross-user/event/institution access still fails via existing Policies |
| AUTH-13 | Seed creates only synthetic `@iches.test`, hashes shared demo `password`, remains idempotent across reruns |
| AUTH-14 | `/dev/login` succeeds only in local environment with explicit flag; 404/403 outside local or when flag off |
| AUTH-15 | No demo credentials/OTP/token leak into CI, public API, session props or Git |
| AUTH-16 | All 4 DB-free Foundation smoke tests still pass; existing 8 DB tests/134 assertions preserved; auth integration opt-in transactions cleanly rolled back |
| AUTH-17 | Normal additive migration only in verified `iches_unisya`, and no destructive resets/other databases |
| AUTH-18 | Existing public `/` and 404 route behavior unchanged; login/dashboard mobile keyboard and validation usable |

## Implementation references

- [21K-08 executable Codex Work Order](21K-08-S0-03-AUTHENTICATION-CODEX-WORK-ORDER.md)
- [21K-06 Owner local fixture decision](21K-06-DEV-DEMO-USER-SEED-POLICY.md)
- [19F Sprint Board](19F-CURRENT-SPRINT-BOARD.md)
- [Laravel 13 authentication](https://laravel.com/framework/docs/13.x/authentication), [signed URLs](https://laravel.com/framework/docs/urls), [local mail log](https://laravel.com/framework/docs/13.x/mail).

**No source, migrations, seeding, push, PR, merge or deployment was executed in preparing this spec.**
