# 21I-01 — ICH-S0-01 Merge and 48-Hour Execution Path

> **28 Oct 2026 · ICH-S0-01 MERGED / COMPLETE · NO NEW CODING GO.** This is the current operational state; older pending/no-go snapshots are historical. The 48-hour planning window is a target, not permission to bypass engineering, database, security or owner approval.

## Accepted foundation — actually verified

- Canonical repo: [akhmadafnan/iches-unisya](https://github.com/akhmadafnan/iches-unisya); only this repository is in scope.
- Owner explicitly authorized merge of [PR #4](https://github.com/akhmadafnan/iches-unisya/pull/4). Accepted feature SHA `8eba7cb5b9da0577910beb919844165869591e05`; **merged into main** at `561baf322d459d898f5f96d280f5a5f02634c59f`.
- Final PR preflight: 55 previously accepted application/evidence files, correct remote SHA, no protected governance/Skills files, no migrations, no conflict. Source is DB-free Laravel 13 + Inertia + Vue 3 + TypeScript + Tailwind 4.
- [21E independent reviewer audit](21E-ICH-S0-01-INDEPENDENT-CODE-REVIEW.md) passed real local Composer, Pint, PHPUnit (3 tests/22 assertions), TypeScript/Vite, Herd HTTP 200 and negative 404 checks. **GitHub Actions/check runs = 0** for the merged PR. Never say CI PASS.
- **No deployment, MySQL migration, auth, CMS, extra coding or new sprint GO** was authorized by PR #4 merge.

## Remaining critical paths

**A — CI before adding business code:** [21I-02 `ICH-S0-07` Work Order](21I-02-ICH-S0-07-CI-WORK-ORDER.md) is `READY_FOR_OWNER_GO`, **NOT AUTHORIZED**. Adds DB-free GitHub Actions/PHP/JS checks on a new feature branch, validating actual CI instead of relying only on local reports.

**B — isolated MySQL test DB before schema:** `ICH-S0-02` is still `BLOCKED_TEST_DB / NO_GO`. The owner-directed phpMyAdmin path in [20E-02](20E-02-HERD-FREE-PHPMYADMIN-WORKFLOW.md) is the preferred way to provision **only** `iches_unisya_test` with limited local `iches_test` account. Provisioning and authenticated isolation are **not yet verified**. Never run destructive `migrate:fresh` against an unknown/non-disposable database, or read unrelated schemas.

**C — protected features:** Post-S0-02, implement under separately named GOs: passwordless OTP/Magic Link `ICH-S0-03` (not the legacy password-login starter kit), scoped RBAC `ICH-S0-04`, private files `ICH-S0-05`, EN/ID/AR RTL shell `ICH-S0-06`, then S1 real CMS/public Academic Editorial/countdown and S2 Text+PDF abstract submission/direct reviewer handoff, per [19B](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md). Without official event start hour, do not fake a D/H/M/S countdown from midnight.

**D — separate design Skills:** [PR #3](https://github.com/akhmadafnan/iches-unisya/pull/3) for 21H visual Skills remains open/unmerged; PR #4 authorization did not extend to PR #3.

## Suggested two-day order (NOT a schedule guarantee)

| Window from future approved coding start | Priority / concrete evidence | Dependency |
|---|---|---|
| 0–4 h | Codex builds `ICH-S0-07` CI; verify real feature PR checks, Composer/Pint/PHP, TS/Vite | Named `GO CODING ICH-S0-07`; latest `main` sync |
| 4–10 h | Isolated test MySQL access only; schema work prep, confirmed empty disposable target | Owner-approved phpMyAdmin setup; separate `ICH-S0-02` GO |
| 10–22 h | Identity/event schema, passwordless auth and event-scoped authorization with negative tests | Named bounded GO per task, test DB green |
| 22–34 h | Inertia EN/ID/AR RTL; authentic public academic editorial; scoped CMS draft/publish | Auth/RBAC/storage and approved task GO |
| 34–44 h | Conditional abstract Text+PDF and reviewer index or CMS hardening, only if prerequisites green | S2/S3 task GOs, protected file tests |
| 44–48 h | End-to-end, responsive/RTL, security and UAT evidence; honest completed/deferred inventory | Real tests + owner acceptance, not forced release |

**Do not promise full S0–S9 conference workflows, verified Finance, publications or deployment in 48 hours.** A tested subset beats fake completeness.

## Next safe action

The local Herd repo `D:\PINJAM-AFNAN\Herd\iches-unisya` last held the previously accepted feature branch. Before any new task Codex must inspect status and **safely fetch and fast-forward local `main`**; never reset/rebase the accepted branch. Only Codex VS Code writes app code after named GO. ChatGPT owns Work Orders and independent PR review; owner alone authorizes merges and production release.

**Next named GO, if owner approves:** `GO CODING ICH-S0-07` referencing [21I-02](21I-02-ICH-S0-07-CI-WORK-ORDER.md). It has not been issued.
