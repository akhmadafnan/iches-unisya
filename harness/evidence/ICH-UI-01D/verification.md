# ICH-UI-01D local verification — 30 October 2026

Owner's explicit GO CODING in the supplied prompt supersedes the prepared Work Order's historical pending status. Work Order read once in full from `D:\PINJAM-AFNAN\ICHES\WORK-ORDERS\ICH-UI-01D-WORK-ORDER-PREPARED.md`.

Preflight: `DESKTOP-PE5D86C`, official `https://github.com/akhmadafnan/iches-unisya.git`, clean `main`, HEAD and local tracking `origin/main` both `e0e21b6dd425cbba93f34bfaee3b6df86c0a189a`. UI-01C `3358f08b4d1f9824e1ecb1ddc2a22a5f7dacdd30` ancestor check exit 0. No fetch or synchronization performed. Feature branch `feat/ich-ui-01d-auth-stock-lyra-shell` created directly from that parent.

## Changes

Eight allowed application paths changed; AppTopbar was already aligned and did not need changes. Desktop sidebar uses neutral sidebar tokens, mobile Sheet stays neutral white with its nested green panel removed. Main content fills available width with the existing header padding. Dashboard cards use simple borders and standard radius. Auth uses a scroll-safe centered max-w-sm column, small identity, 2xl heading and full-width actions. Login shows one OTP form at a time, with direct Verify access, Request/resend navigation, email retention and focus movement. Existing useForm endpoints, validation, status and processing remain. Demo and Magic only change composition.

No backend, routes, database schema, global themes, UI primitives, i18n files, dependencies, governance or toolchain edits. Browser QA submits only existing synthetic local demo accounts to existing endpoints; it consequently writes normal local sessions, rate-limit state and synthetic challenges. No migration, seeding or reset was run. No credentials, challenges, cookies or mail logs are captured in evidence.

## Executed checks

| Command | Exit | Result |
|---|---:|---|
| `npm.cmd run typecheck` | 0 | PASS |
| `npm.cmd run build` | 0 | PASS, Vite 7.3.7, 3382 modules |
| `php artisan test --compact --no-ansi` | 0 | PASS, 30 tests / 684 assertions, 65.64 seconds |
| `$env:ICHES_QA_AUTH_ONLY='1'; node harness/evidence/ICH-UI-01D/browser-qa.cjs` | 0 | PASS, 64 checks / 12 viewport measurements; `browser-results.json` |
| `$env:ICHES_QA_DASHBOARD_ONLY='1'; $env:ICHES_QA_PROFILE='<existing disposable QA profile>'; node harness/evidence/ICH-UI-01D/browser-qa.cjs` | 0 | PASS, 91 checks / 18 measurements; `dashboard-results.json` |
| `git diff --check` | 0 | PASS |

Actual local PHP is 8.4.24 and Node is 26.4.0. PHP uses the repository's DB-disabled test configuration; DB test suite not run. Node 22 / remote CI not run and toolchain not changed.

Browser: real headless local Chrome through native CDP, actual Herd app and built assets (no public/hot). Mouse clicks, text entry, Enter, Tab, Shift-Tab, ArrowDown and Escape are native CDP input. Locale and workspace select changes use synthetic DOM change events, and history checks invoke history APIs. Focus is programmatically placed on the desktop collapse button before native Enter. This is not physical keyboard or screen-reader UAT.

Earlier harness attempts returned exit 1 while correcting reused Runtime.evaluate lexical declarations, native-input timing and a too-strict Sheet expectation (stock white is permitted alongside near-white). Repeated Auth submissions then exercised the existing 15-minute throttle. Successful request, email copying, code focus, invalid verification and successful resend/error clearing were observed before that limit. Final Auth run explicitly verifies readable request/resend throttle errors instead of resetting or bypassing the limiter. Final dashboard run reused an isolated QA profile after an earlier real demo login, then verified workspace switching and logout. Final reports reflect only their own executed assertions.

## Acceptance matrix

| AC | Status | Evidence |
|---|---|---|
| AC-01 | PASS | Exact clean baseline, official remote, device, ancestor exit 0, direct feature parent |
| AC-02 | PASS | Eight application paths; only permitted append-only evidence/log exceptions |
| AC-03 | PASS | Desktop computed sidebar `oklch(0.985 0 0)`; active neutral selection; native Enter collapse/expand in all locales; source hover/focus tokens |
| AC-04 | PASS | White portal Sheet, no colored inner panel, correct RTL side, native Tab/Shift-Tab trap, Escape and close restore trigger focus |
| AC-05 | PASS | Dashboard screenshots/measurements at 390/768/1440/1920; header/main padding 16px or 32px; global max-width removed |
| AC-06 | PASS | Existing synthetic account/roles displayed; workspace persisted via existing server endpoint with actor unchanged; no invented routes/data |
| AC-07 | PASS | Auth max 384px, horizontal and vertical centering when fitting; 390x360 starts at positive y and scrolls |
| AC-08 | PASS | Direct Verify/Request focus in EN/ID/AR; successful request/resend before throttle; invalid Verify retains errors and retries; final run also checks throttle feedback; unchanged endpoints |
| AC-09 | PASS (bounded coverage) | Actual local demo login redirected; existing DB-free tests cover local-only guards; Magic confirmation POST/error/new-code link and unchanged actions verified. Valid OTP/Magic redemption NOT_RUN |
| AC-10 | PASS (automated) | EN/ID/AR direction/locale, back/forward restoration, native keyboard focus, labels, status and error feedback; no screen-reader claim |
| AC-11 | PASS | Computed Inter/Sora Auth and JetBrains/Geist Admin, both with Noto Sans Arabic fallback; global theme files untouched |
| AC-12 | PASS | Real demo login, workspace switch, dropdown keyboard, Sheet portal, logout and protected-dashboard redirect |
| AC-13 | PASS (emulated) | 390/768/1440 plus 1920 Admin, 390x360 Auth/Sheet; no measured horizontal overflow; native actions can scroll to controls |
| AC-14 | PASS after local commit | Four mandatory commands exit 0; single commit/parent and final clean tree supplied by Git handoff |

## Limits and handoff

Valid OTP/Magic Link redemption, physical mobile keyboard, screen-reader UAT and Node 22 CI are NOT_RUN. No mail code/token was read or exported. No backend security claim beyond the executed existing suite and exercised routes. Screenshots show only existing synthetic local accounts; they are actual browser captures.

`changed-paths.txt` lists all committed paths. The single local commit SHA and parent must be obtained from Git, avoiding a self-referential commit hash inside this file. Stop after local commit: independent review and owner push/PR approval remain separate gates. No push, PR, merge, deploy or subsequent task execution authorized. Historical board/governance snapshots remain untouched; ChatGPT can update them in its own authorized documentation workflow.
