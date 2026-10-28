# 21K-07 — ICH-S0-03 Authentication Foundation: Codex Work Order

> **PRECEDENCE UPDATE (28 October 2026):** This earlier draft Work Order is preserved for historical traceability. The **current authoritative** S0-03 implementation contract is [21K-07 Authentication MVP Spec](21K-07-S0-03-AUTHENTICATION-MVP-SPEC.md) together with [21K-08 exact Codex Work Order](21K-08-S0-03-CODEX-WORK-ORDER.md). If this draft suggests only one of OTP/Magic Link, different tables/seeds, or a different authorization status, follow 21K-07/08. Per 20B, named **`GO CODING ICH-S0-03`** is still required. No source implementation was authorized by this earlier draft.

> 28 October 2026 · READY_FOR_CODEX / NO NAMED GO YET. Planning and acceptance criteria only. Owner's request to continue planning does not itself authorize application code, DB migrations, deployment, push, PR merge or changes to PR #3.

## Baseline verified
- Repository: `akhmadafnan/iches-unisya`, default `main`; latest checked commit `ef18ac22eeee68bad4777d78450df143a9774382`.
- S0-01, S0-07, S0-02 merged/complete, PR #6 merged. Existing `users` has UUID primary key, normalized unique email and `email_verified_at`, **no password**; `User.php` has no password trait/cast; `routes/web.php` only has Foundation; `config/auth.php` absent (framework defaults may apply; inspect before choosing).
- Reference: [02 Account Registration](02-AKUN-REGISTRASI-KEIKUTSERTAAN.md), [19A Working Protocol](19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md), [19B Backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md), [21K-06 Demo Policy](21K-06-DEV-DEMO-USER-SEED-POLICY.md).

## Product outcome / in scope
1. Single account, **passwordless attendee** registration/sign-in with email-based one-time code **or** Magic Link. Codex should select the simplest fully testable primary path based on actual Laravel foundation; do not build two parallel systems merely for scope inflation. Allow verified users to log out and securely resume session. A synthetically verified local account must not imply production mailbox ownership.
2. Reliable one-time challenge: generate cryptographically strong token/OTP, store only a digest, short expiry, single-use atomic redemption, invalidate superseded challenges, bound attempts/resends, generic responses for unknown email, and no sensitive token/OTP in logs/error traces. Handle concurrent redemption; no open redirect.
3. Session-backed Laravel authentication, CSRF protection, regeneration on successful login, invalidate session and regenerate CSRF on logout. Guest-only auth forms and protected sample profile/dashboard route. No role escalation; keep S0-02 Policy restrictions and UUIDv7 behavior.
4. Minimal responsive Inertia/Vue UI compatible with existing project: email entry, request, verify/link confirmation, expired/retry, signed-in state and logout. Do not redesign ICHES public website; no multi-page CMS.
5. Local testing via application log or local inbox; no real email service/deployment prerequisite. All local dummy addresses end in `@iches.test`; never send them to an external provider.
6. A **dedicated, explicit, repeatable development demo seeder**, whose accounts are local-only and synthetic. Include `admin@iches.test`, `reviewer@iches.test`, `presenter@iches.test`, `participant@iches.test` only for valid role/scope relationships; do not grant powerful roles to every demo user. Seeder runs only with local/testing environment AND explicit `ICHES_DEMO_MODE=true`, and must refuse staging/production. Do not run on normal production deploy or routine CI.
7. **Demo shared password**: owner wants literal `password` on every demo account *if local password login is implemented*. Hash only (Laravel Hash); gate developer password route AND credential use to local/testing + demo flag, fail closed elsewhere. Preserve passwordless production attendee auth. Prefer avoiding any schema password column unless essential for a useful dev-only login; alternatively document why a new forward-only nullable migration is necessary. No authentication bypass based merely on `@iches.test` suffix.
8. Document concise manual local smoke-test and production-off checklist in sanitized test evidence/build log.

## Out of scope
S0-04 complete RBAC/workspace switch; reviewer assignment; CMS/countdown; conference activities, paper submission; Finance/payment; staff MFA design; real SMTP/domain verification; reset/truncate/`migrate:fresh`; PR #3 modification; production deploy; auto merge; copying old application's auth wholesale.

## Permitted implementation paths (after separate owner GO)
`app/**`, `bootstrap/app.php` only if necessary, `config/auth.php` / `config/mail.php` only if necessary, `routes/web.php`, `resources/js/**` narrowly for auth UI, `database/migrations/**` *new forward-only files* if necessary, `database/seeders/**`, `tests/**`, `.env.example` non-secret flag examples, and append-only `harness/build-log.md` / sanitized `harness/evidence/**`. Dependency/lockfile changes only if justified in PR; do not edit existing S0-02 migrations.

## Implementation sequence for Codex
- Preflight: inspect `git status`, branch and latest `main`, Laravel auth defaults/config, model, middleware and DB migrations; report any uncommitted work. Create `feat/ich-s0-03-auth-foundation` from freshly fetched `main` only after named GO.
- Implement backend challenge and authenticated session first; use DB store with a narrowly scoped forward migration if persistence needed. Avoid leaking whether an email exists; apply Laravel rate limit to request and verify actions per email+IP with practical dev limits.
- Implement smallest viable auth screens and idempotent dev seeder/optional local demo password facility, with explicit opt-in flag and synthetic institution/event grants if appropriate.
- Run targeted tests + full available DB-free suite; optionally run transaction-isolated integration tests on owner's **existing** development MySQL schema only under a separately confirmed DB operation gate. Never reinitialize DB. Run lint/style, TypeScript, build, and `git diff --check`.
- Report actual changed paths, tests/assertions, CI boundaries, DB effects, known risks; offer commit/push only within explicit permissions. Owner later separately approves PR merge.

## Mandatory acceptance tests
- Register/request works, resend invalidates old challenge; invalid, expired and already used token rejected; parallel replay cannot succeed twice.
- Successful verification creates/logs in the same normalized email user (no duplicates) and sets verified timestamp appropriately; session rotates; logout actually invalidates.
- CSRF and unauthenticated access denied; auth routes throttle; invalid token or unknown account does not reveal existence; no attacker-controlled redirect.
- Dev seed repeat runs do not duplicate; `@iches.test` account roles scoped correctly; common demo password accepted **only if** explicitly designed local demo password path exists and flag is enabled.
- Demo login/seeding denied in production/staging **even if flag accidentally set**, no weak default password can authenticate there. `@iches.test` domain alone never unlocks access.
- Existing UUIDv7 / event+institution Policies remain unaffected. CI PHP 8.4 and Node 22 remain green; report DB-free vs DB-backed checks separately.

## Delivery contract
**Current status: READY_FOR_CODEX, AUTHORIZATION: NO_GO.** Owner may grant `GO CODING ICH-S0-03` with explicit limits for code, DB migrations and branch publishing. No presumed authorization for DB reset, deployment or main merge. Codex owns all code changes in VS Code, ChatGPT owns spec/review/GitHub governance.
