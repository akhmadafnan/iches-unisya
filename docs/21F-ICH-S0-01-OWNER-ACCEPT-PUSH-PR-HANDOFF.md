# 21F — Owner Acceptance & Codex Push / GitHub PR Handoff (ICH-S0-01)

> **OWNER_ACCEPTED / PUSH_AND_CREATE_PR_AUTHORIZED — 28 October 2026.** This does **not** authorize merging the PR, changing source code, database/migrations, CMS, auth, deployment, or the next sprint.

## 1. Exact scope of owner's approval

Owner's message in this ChatGPT conversation:

> “SETUJU ACCEPT ICH-S0-01. Saya menerima hasil review 21E dan mengizinkan Codex VS Code melakukan push branch feat/ich-s0-01-foundation serta membuat Pull Request ke main. Tidak ada izin auto-merge, migrasi database, atau deployment. Siapkan instruksi Codex berikutnya.”

- **Accepted task:** `ICH-S0-01` only — independently audited as `REVIEW_PASS` in [21E](21E-ICH-S0-01-INDEPENDENT-CODE-REVIEW.md).
- **Accepted local commit to publish:** `8eba7cb5b9da0577910beb919844165869591e05`.
- **Feature branch:** `feat/ich-s0-01-foundation`, existing locally. **Do not recreate, reset, rebase, amend or cherry-pick it.**
- **Original application branch base:** `4137dce48e85fab5c25c89b7f3a1fde1d0fa3b89`. This remains a valid ancestor; GitHub `main` has advanced only through a separate ChatGPT governance documentation commit since then.
- **Canonical repository:** `akhmadafnan/iches-unisya`; local root `D:\PINJAM-AFNAN\Herd\iches-unisya`.
- **Review state:** local Git clean; source files validated; **push and feature PR had not occurred at last review**.
- **GitHub `main` HEAD at handoff preparation:** `e80670f84ad8fcd66ebec2fe92b45d99ec74a19f`. May advance in future; inspect it read-only before opening PR.
- **Allowed by owner now:** Codex may push *only* this existing feature branch and open a PR into `main`. Creating or changing app code is **not** necessary or authorized by this push-only instruction.

## 2. Pre-push hard checks — Codex in VS Code

Use the exact existing local repo. Execute these from the VS Code terminal as read-only checks:

```powershell
git status --short --branch
git remote -v
git branch --show-current
git rev-parse HEAD
git rev-parse main
git fetch origin main
git merge-base --is-ancestor 4137dce48e85fab5c25c89b7f3a1fde1d0fa3b89 HEAD
git diff --name-status origin/main...HEAD
git diff --check origin/main...HEAD
```

**Required:**
- Branch exactly `feat/ich-s0-01-foundation`, HEAD exactly accepted SHA above, worktree clean, origin correct.
- No changes to `AGENTS.md`, `PLANS.md`, `WORKSPACE.md`, `README.md`, `docs/**`, or `.agents/skills/**`; no `.env`, secrets, schema/migrations, generated dependencies/build files.
- The three-dot diff from the correct merge base should show **the 55 reviewed application/evidence files**, not the newer GitHub governance docs. Do **not** merge or rebase `main` into the feature branch merely to pull newer docs.
- If unexpected changes, divergent history, wrong branch, credential prompts that cannot be safely handled, or permission problems: **STOP**, report rather than forcing.

## 3. Single permitted Git write: publish the already accepted commit

After preflight passes:

```powershell
git push -u origin feat/ich-s0-01-foundation
```

- **No `--force`, `--force-with-lease`, `push --all`, pushing `main`, or commits/amendments.**
- Do not print, copy, screenshot or store GitHub credential/token data in chat/logs.
- Verify remote branch exists with expected HEAD. If GitHub rejects the push, stop and return the error category without exposing tokens.

## 4. Open a Pull Request — do NOT merge

The connected Windows environment did **not** resolve GitHub CLI `gh` at read-only preflight. **Do not install tools or modify global CLI configuration just to create the PR.**

After push, use the authenticated GitHub UI if available:

[Create PR from feature to main](https://github.com/akhmadafnan/iches-unisya/compare/main...feat/ich-s0-01-foundation?expand=1)

Set:
- Base repository: `akhmadafnan/iches-unisya`.
- Base branch: **`main`**.
- Compare/head: **`feat/ich-s0-01-foundation`**.
- Title: **`feat: bootstrap DB-free Laravel/Inertia/Vue foundation (ICH-S0-01)`**.
- Mark as **Draft PR** if the UI permits; the reviewer/owner decides when to mark ready or merge.

**Suggested PR description** (copy faithfully and keep it factual):

```markdown
## ICH-S0-01 — Database-independent application foundation

Owner accepted task ICH-S0-01 after independent ChatGPT audit 21E; owner authorized Codex to publish this branch and open a PR. **This PR does not have merge authorization.**

### Scope
- Laravel 13.39.0 + Inertia Laravel 2.0.28 + Inertia Vue 2.3.29 + Vue 3.5.44 + TypeScript 5.9.3 + Tailwind4 4.3.3 + Vite 7.3.7
- Minimal truthful Inertia/Vue landing page served by Herd Free at http://iches-unisya.test/
- DB deliberately disabled; local file sessions/cache and sync queue
- Smoke test, dependency lockfiles and sanitized evidence

### Independent review and verification
- Review: docs/21E-ICH-S0-01-INDEPENDENT-CODE-REVIEW.md (on main)
- composer validate --strict: PASS
- php artisan test: PASS, 3 tests / 22 assertions
- PHP Pint: PASS
- npm.cmd run typecheck: PASS
- npm.cmd run build: PASS
- Composer platform requirements: PASS
- Herd GET /: 200; unknown route: 404; private/config URL probes: 404
- Git scope: no governance edits, no tracked .env, no schema/auth/CMS

### Exclusions
No database migration, authentication, CMS, abstract workflow, finance, deployment or production data. Empty favicon is an acknowledged non-blocking public-design follow-up.

### Merge policy
**NO AUTO-MERGE.** Keep this PR unmerged until a separate explicit owner authorization. No next-task GO is implied.
```

**If Codex cannot use the authenticated GitHub UI:** do **not** install new GitHub CLI or attempt credentials extraction. Instead return the **verified successful push**, the compare URL above, and reason PR creation is blocked. ChatGPT has an existing GitHub connector and can open the PR separately from the published branch, using this same description; this fallback does not authorize merge.

## 5. Final handoff required from Codex

Return: `ICH-S0-01`, branch, actual pushed commit SHA, remote branch confirmation, PR number/link and state (draft/open), target `main`, no files changed after acceptance, no force push, status clean; if PR not created, report exactly what is blocked and compare URL.

**Stop once feature PR is opened**. No automatic merge, deployment, code revision or next task.
