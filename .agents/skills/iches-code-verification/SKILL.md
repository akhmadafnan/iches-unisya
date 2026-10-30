---
name: iches-code-verification
description: Run and report real verification for owner-authorized ICHES Laravel and Vue changes. Use on feature implementation, migration review, CI failures, PR handoff, backend security tests, frontend typecheck/build, browser mobile and RTL QA. Not for planning or permission grants.
---
# ICHES Code Verification — Evidence-First

## CURRENT VERIFICATION POLICY — 30 October 2026

- Verify actual branch/HEAD, parent, remote, allowlist, diff and working tree; never infer an action GO from this Skill. S0-01..07, UI-01B/01C/01D and S1-01..04 are merged. S1-05 is next audit/plan only, with no GO CODING.
- Only the **owner** runs Codex VS Code; ChatGPT handles independent review/governance. Explicit GO needed for coding, extra test/database operations, push/PR, merge and post-merge sync. Development DB `iches_unisya`, isolated CMS test `iches_unisya_cms_test`; no DB mutation without separate permission.
- **Visual UAT:** Local Browser against actual Herd `.test` Laravel app with development data; text **URL | scenario | expected | observed | PASS/FAIL/BLOCKED | findings**. Do not create new screenshot, PNG/JPG/WebP, rendered fixture or image evidence. Historical captures preserved. Unavailable browser/database = BLOCKED.
- Preserve Laravel 13/Inertia/Vue 3/TS/Tailwind 4, scoped authorization/private storage, EN/ID/AR RTL; Backoffice Lyra V1.1 `a33J5lFw`; public Academic Editorial CMS. See [AGENTS](../../../AGENTS.md) and [19F](../../../docs/19F-CURRENT-SPRINT-BOARD.md) for current state.



**No GO = no implementation or test migration.** Read `AGENTS.md`, active Work Order and engineering Skill; inspect actual Composer/npm scripts before selecting commands.

| Scope | Verification checklist |
|---|---|
| Laravel foundation | PHP feature/unit smoke, dependencies, bootstrap, reproducible build |
| Database | FK/unique, migration/rollback **only with explicit separate GO TEST/DB** on the specified isolated schema; otherwise BLOCKED |
| Auth/RBAC/private files | 401/403, cross-user/event/institution denial, private file download rejection |
| Admin/CMS | Server authorization, draft/preview/publish/rollback, browser responsive and RTL |
| Countdown | Fake clock boundaries/timezone, no invented 00:00 |
| Abstract | Text+PDF validation, owner/file protection, direct Reviewer Index without screening |
| Finance | Real bank mutation match before Verified, concurrency and duplicate denial |
| OJS/QR | Immutable snapshots, scoped export, idempotency/scan conflict checks |

## Procedure
1. State Task IDs, branch, in-scope paths, test environment and actual command plan.
2. Run relevant tests/lint/typecheck/build/browser checks; report **exact command and exit code** for PASS/FAIL/BLOCKED.
3. Fix only defects caused by the authorized task; stop on unrelated or uncertain requirements.
4. Inspect diff for out-of-scope changes, secrets, real data and risky migration; check `git diff --check`.
5. Save **sanitized** command summaries/evidence; no personal records, payment details, .env, credentials or raw production logs.
6. Append to `harness/build-log.md` **only after explicit named Task/Sprint GO**, preserving previous entries. Never update 19F, PLANS, product decisions or Skills.

## Handoff fields
```text
Task IDs:
Authorization:
Branch, commit, PR, changed paths:
Command | exit code | PASS/FAIL/BLOCKED | evidence:
Security negative cases:
Migration test DB / rollback:
Real browser UAT TEXT (URL | scenario | expected | observed | PASS/FAIL/BLOCKED | findings); keyboard / mobile / RTL:
Open blocker and next authorized step:
```

**Successful compilation is not sufficient for DONE; review and owner acceptance are separate gates.**

[20B governance](../../../docs/20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md)
