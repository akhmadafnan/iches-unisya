# 21I-09 — Owner ACCEPT REV-02 and Codex-only corrected SHA push

> **28 Oct 2026 · HISTORICAL OWNER PUSH AUTHORIZATION — NOW COMPLETED.** Exact corrected SHA was pushed; PR #5 live CI run succeeded. See [21I-10 verified CI and current merge gate](21I-10-S0-07-LIVE-CI-PASS-AND-MERGE-GATE.md). The remainder preserves pre-push instructions as a handoff snapshot. The owner expressly authorized Codex VS Code to push `294a54b223676df3b6e9124480e271ee3a50bdc7` normally on `feat/ich-s0-07-ci`. ChatGPT may inspect PR #5 and GitHub Actions after verified push. **No PR merge, migration, database, other coding task or deployment permission.**

## Verified state before handoff

- Canonical repo: `D:\PINJAM-AFNAN\Herd\iches-unisya`, canonical remote `https://github.com/akhmadafnan/iches-unisya.git`.
- Checked-out branch: `feat/ich-s0-07-ci`; exact local HEAD `294a54b223676df3b6e9124480e271ee3a50bdc7`; working tree **clean**.
- Existing tracking branch `origin/feat/ich-s0-07-ci` still at `c420d95175d7d25cd9fee6719180acaf0b684903`. GitHub verified remote SHA still old.
- [21I-08](21I-08-S0-07-REV02-FINAL-LOCAL-REVIEW.md) review PASS: test-only lower-case Inertia path, negative missing component assertion, runner-local `cp .env.example .env`; independently run 4 PHP tests/36 assertions, Pint and Composer PASS.
- [PR #5](https://github.com/akhmadafnan/iches-unisya/pull/5) OPEN/UNMERGED, old CI runs FAILED. New SHA Linux/Node22 not tested until push.
- `origin/main` may be ahead by docs-only commits; do not merge/rebase/reset/amend the accepted feature branch.

## Codex VS Code — publication-only prompt

~~~text
ICH-S0-07 REV-02 — ACCEPTED / NORMAL PUSH AUTHORIZED

Work ONLY in D:\PINJAM-AFNAN\Herd\iches-unisya with Codex VS Code.
Owner explicitly authorizes a normal non-force push of existing branch feat/ich-s0-07-ci at EXACT SHA 294a54b223676df3b6e9124480e271ee3a50bdc7.
Do not edit files, run migrations, change code, rebase, reset, amend, merge branches or deploy.

1) Read docs/21I-09-S0-07-REV02-PUSH-AUTHORIZATION.md from origin/main; inspect git status, branch, HEAD, remote and upstream.
2) STOP if branch != feat/ich-s0-07-ci, HEAD != 294a54b223676df3b6e9124480e271ee3a50bdc7, working tree dirty, origin != akhmadafnan/iches-unisya, or upstream is unexpected.
3) Verify remote current branch tip is c420d95175d7d25cd9fee6719180acaf0b684903 or exact expected new commit; do not overwrite any unexpected remote commit. If already at new commit, do not push again.
4) Run git push origin feat/ich-s0-07-ci (no --force, no extra refs/tags). Authenticate with existing Git Credential Manager if needed; do not print secrets.
5) Verify remote SHA equals 294a54b223676df3b6e9124480e271ee3a50bdc7, tracking branch active, local worktree clean, and report push exit code, branch/commit and PR #5 link.
6) STOP; do not merge PR #5 or modify other workflows. ChatGPT will inspect actual GitHub Actions.

NO additional GO CODING, DATABASE, MIGRATION, MERGE OR DEPLOY.
~~~

## Follow-up acceptance gate

After Codex reports push exit 0, ChatGPT checks remote SHA on GitHub, PR #5 remains only the three approved changed files (workflow, test and append-only log), and GitHub Actions run results on Ubuntu 24.04/PHP8.4/Node22. New **actual** CI PASS is required before any merge recommendation. Any CI fail → record exact job/step; do not claim success or merge.
