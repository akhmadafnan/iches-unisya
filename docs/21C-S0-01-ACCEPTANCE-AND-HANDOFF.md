# 21C — ICH-S0-01 QA, Acceptance and Handoff

> **Verification contract only; NO GO CODING.** First bootable website is NOT a finished conference system.

## Sequence

1. **COMPLETED:** owner authorized `MERGE PR #1`; ChatGPT checked final diff/mergeability, GitHub merged at `275cfbfd0e431033eb05d2c519eda19282465962`, and local `main` synced clean.
2. [21B Work Order](21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md) now records approved governance merge SHA. Codex will confirm exact current `main` HEAD and branch-point SHA at start. A separate owner message **`GO CODING ICH-S0-01`** remains required.
3. Codex works code-only on `feat/ich-s0-01-foundation`, within Herd repo, without DB/schema/auth.
4. Codex builds/runs/verifies and sends exact command outputs + sanitized evidence.
5. ChatGPT reviews diff and quality; owner separately authorizes implementation PR merge, never automatic.

## Acceptance matrix

| Check | PASS means |
|---|---|
| Git safety | Correct Herd path and remote, feature branch, no docs/secret/unrelated changes |
| Laravel | `php artisan --version` Laravel 13; PHP 8.4 compatible |
| Vue/Inertia | GET / renders actual Inertia/Vue page (not fake static HTML) |
| CSS/TS | Tailwind 4 and Vite build succeeds, TS checked |
| Herd URL | `http://iches-unisya.test/` returns HTTP 200 (before Task GO expected 404) |
| Smoke | DB-free GET / returns 200 and unknown path returns 404 |
| Auth/DB scope | No migrations, no password-based auth forms, no production data |
| Evidence | Actual commands, exit codes, branch, diff, build-log append, honest blockers |

**Not yet accepted or executed.** Next tasks `ICH-S0-02` (test DB UUIDv7 schema), `ICH-S0-07` (quality pipeline), then `ICH-S1-01..08` (real CMS and original website), each with its own gate/GO.

Source of status: [19F Board](19F-CURRENT-SPRINT-BOARD.md).
