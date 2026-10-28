# 21K-03 — Owner ACCEPT S0-02 REV-03 / Codex-only Exact-SHA Push Handoff

> **28 October 2026 · OWNER_ACCEPTED / PUSH_AUTHORIZED / NOT YET PUSHED.** Owner explicitly authorized Codex VS Code normal, non-force push of feature **`feat/ich-s0-02-db-foundation` at exact `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`**, after accepting [21K-02 independent review](21K-02-S0-02-REV03-FINAL-LOCAL-REVIEW.md). Once remote SHA is independently verified, ChatGPT is authorized to open a PR and check the actual GitHub Actions PHP 8.4 / Node22 execution. **No merge, DB reset, app-code changes, other task or deployment authorized.**

## Independent preflight before Codex push

- Workspace: `D:\PINJAM-AFNAN\Herd\iches-unisya`, canonical origin `https://github.com/akhmadafnan/iches-unisya.git`.
- Checked out branch `feat/ich-s0-02-db-foundation`; exact local HEAD `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`, parent `78bc6d9a904c86b334c28f645d908452295ba26e`, `git status --porcelain` clean, no upstream set. GitHub feature ref was **NOT_FOUND** prior to publication.
- Original S0-02 feature baseline `3ad72cfb99f4f5015203efb81f29563d49b6357c`. Whole branch S0-02 diff includes **14 paths**: initial implementation 13 paths and one additional corrective migration; 2 commits `78bc6d9`, `f4eb25c`.
- REV-03 local independently **REVIEW_PASS**: 8 DB tests/134 assertions, 4 DB-free tests/36 assertions, Pint 29 files, Composer, npm TS/Vite PASS; `iches_unisya` batch 1 and 2 migrations both Ran; real schema uniqueness and empty domain tables verified after transactional fixture rollback. GitHub Actions for this unpublished source **NOT_RUN**.
- **`main` is ahead with governance docs** (recent documented head `2a7a03014309df84ec4ce28c73670c215b2adbde` and this handoff's subsequent docs commit). Do **not** merge, rebase or fast-forward `main` into the accepted feature branch before the approved exact-SHA push. PR comparison will be handled on GitHub after publication.

## Codex VS Code publication-only sequence

1. Read this file via `origin/main:docs/21K-03-S0-02-OWNER-ACCEPT-CODEX-PUSH-HANDOFF.md` and [21K-02](21K-02-S0-02-REV03-FINAL-LOCAL-REVIEW.md). Verify canonical origin, clean status, exact branch, exact HEAD/parent, no unexpected existing remote branch. Stop if any mismatch.
2. **NO code edits, new commits, amend, reset, rebase, merge, DB migration, SQL or test fixture writes.** Keep the local HEAD pinned to `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`.
3. Check remote feature ref once. If it does not exist, run **`git push -u origin feat/ich-s0-02-db-foundation`** (normal non-force). If it already exists at the exact approved SHA, skip duplicate push; if it points anywhere else, STOP and report, no overwrite.
4. After push, check GitHub remote SHA equals exact approved `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`; tracking active, clean worktree. Report exit code, exact remote SHA and branch, PR not yet opened.
5. **STOP.** ChatGPT, not Codex, will create PR and verify actual Ubuntu PHP8.4/Node22 CI. Even passing CI does not grant merge authorization.

**DO NOT** push other branches/tags, use `--force`, alter or expose local root credentials, edit `.env`, reset development DB, merge, deploy or start S0-03/04.
