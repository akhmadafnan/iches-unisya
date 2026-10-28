# 21L-03 — ICH-S0-04 MERGED / COMPLETE

> **28 October 2026 — Owner-scoped GO MERGE PR #10 executed successfully.** ICH-S0-04 scoped authorization and Switch Workspace code milestone is now **MERGED / COMPLETE**. This does not authorize deployment, migration, database reset, next coding task, or merging PR #3.

## Owner authorization and premerge checks

Owner authorized **normal merge without bypass** of PR #10 only after verifying exact head SHA `7edd837eb7285b3dbed0dbeacc2eb5546d76e960`, exactly **16 reviewed files**, successful PHP 8.4/Node 22 Actions, mergeability and required checks. User required a hard stop rather than forcing merge in case of any issue.

| Verification | Actual finding |
|---|---|
| Pull request | [#10](https://github.com/akhmadafnan/iches-unisya/pull/10), targeting `main`, OPEN and non-draft immediately before merge |
| Head source SHA | **`7edd837eb7285b3dbed0dbeacc2eb5546d76e960`** — exact match |
| Scope | **16 changed paths**, the exact reviewed Workspaces resolver/controller/middleware, two Policies, SessionController, Vue dashboard, routes, DB/DB-free tests and sanitized harness evidence |
| Feature commits | Exactly **1** |
| CI run | [GitHub Actions run #40716707863](https://github.com/akhmadafnan/iches-unisya/actions/runs/40716707863), `completed/success`, job `PHP 8.4 and Node 22 checks` success, including locked Composer/npm dependencies, DB-free PHP tests, Pint, TypeScript and Vite build; no skipped critical stages |
| GitHub check runs | Two separate `PHP 8.4 and Node 22 checks` for the exact feature SHA both `completed/success` |
| Mergeability | `mergeable=true`, `mergeable_state=clean` |
| Branch rules | `main protected=false`, required checks enforcement `off` and zero contexts/checks; active repository/parent rulesets query returned empty list. **No bypass requested or used.** |
| Main evolution | Base branch accumulated documentation commits since the feature branch was prepared; GitHub reported conflict-free clean mergeability, no rebase or force needed |

## Actual merge transaction

- GitHub `merge_pull_request` called with `pr_number=10`, `merge_method=merge`, and **`expected_head_sha=7edd837eb7285b3dbed0dbeacc2eb5546d76e960`** concurrency guard; no bypass option used.
- GitHub result: **`merged=true`**, "Pull Request successfully merged"; merge SHA **`1942db7def41a1c839d2bf86936880ad689ed178`**.
- Postmerge PR check: `state=closed`, `merged=true`, merge SHA exactly above, head SHA unchanged, **16 files**, one feature commit.
- Independently fetched `main` immediately after merge: HEAD **`1942db7def41a1c839d2bf86936880ad689ed178`**.
- Independently verified GitHub signed merge commit object: **parent 1 `cfc12603850112a3c6e95e99518a3caf6a40e203`** (premerge main), **parent 2 `7edd837eb7285b3dbed0dbeacc2eb5546d76e960`** (exact authorized feature). This is a normal 2-parent merge, **not squash, rebase or force**. GitHub merge commit file list has **16** files, **382 insertions / 9 deletions**.
- Governance-only documentation commits after the merge may advance `main` while preserving this exact verified merge in history.

## Evidence and remaining boundaries

- Previously reviewed independently in [21L-02](21L-02-S0-04-INDEPENDENT-LOCAL-AUDIT.md): DB-free PHP **12/131 PASS**, transaction-isolated owned-local MySQL **21/408 PASS**, scoped workspace **3/100 PASS**, Composer, scoped Pint, TypeScript/build and diff checks. Real authenticated browser E2E remains not fully exercised; synthetic intercepted Inertia dashboard browser fixture was checked. GitHub CI is intentionally DB-free and cannot substitute for actual MySQL or production UAT.
- 14 Windows whole-repo Pint CRLF/old format observations did not recur in remote Linux Pint, which passed for this head.
- No MySQL schema migration, `migrate:fresh`, reset/wipe, deployment, extra application code, CMS, full impersonation, bank operations, or unrelated PR #3 merge was performed by ChatGPT.
- Product invariants remain: **single account with multiple event/institution-scoped roles**; switching workspace keeps original identity, revocation causes Personal fallback; backend Policies deny cross-tenant access. Demo `@iches.test` is development-only; public frontend visual system remains locked (Inter + Sora, ivory/forest/near-black/gold; COMING SOON flyer informational only).
- [Issue #9](https://github.com/akhmadafnan/iches-unisya/issues/9) can be closed **completed** after this document is recorded. Next coding task and any real deployment require separately scoped owner authorization.

**Official status: ICH-S0-04 = MERGED / COMPLETE.**
