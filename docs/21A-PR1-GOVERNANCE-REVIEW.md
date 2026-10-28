# 21A — Governance PR #1 Review and Approval Gate

**28 October 2026 · OWNER-APPROVED & MERGED (PR #1).** GitHub merge commit `275cfbfd0e431033eb05d2c519eda19282465962`; no GO CODING.

## Verified findings

- PR [#1](https://github.com/akhmadafnan/iches-unisya/pull/1): `docs/19e-agent-workspace` → `main`.
- Initial review: `main` SHA `5d8a6770cfc569a04d339eb1792f990227b1c04c`; docs head `5003e2a825945f4b86abc2dfceae8bb1b3252b57`; 17 commits ahead, 0 behind, GitHub `mergeable=true` / `clean` at check time.
- **24 changed files**, all Markdown governance/Skills, **zero PHP/Vue/JS/TS application files**.
- Checked **263 relative Markdown links**, **zero missing targets** in the changed docs; Git working tree clean. This is structural review, **not runtime/CI test**.
- Verified current local checkout `D:\PINJAM-AFNAN\Herd\iches-unisya`; Herd Free 1.29.0 maps `http://iches-unisya.test` to this checkout, PHP 8.4. Existing HTTP 404 is expected until application exists.
- Codex PRE-03 read four Skills and master rules successfully; RD-01–RD-06 owner-approved. In particular, participant login must remain **passwordless Email OTP/Magic Link**, not default email/password.
- Local runtime PHP 8.4.24 / Composer 2.10.1 / Node 26.4 / npm.cmd 11.17 and all **13 documented Laravel 13 minimum PHP extensions** checked present. Application build/tests NOT RUN.

## Governance consistency updates in this PR

- Root `AGENTS.md` and engineering Skill reference **approved RD-01..06**, not former proposal status.
- 20B distinguishes **one-task DB-free `ICH-S0-01`** from three-task Sprint-01, whose `S0-02` migrations still require an isolated test DB.
- [21B Work Order](21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md) scopes a safe Laravel bootstrap, with explicit authorization and test commands; [21C](21C-S0-01-ACCEPTANCE-AND-HANDOFF.md) defines acceptance.
- Snapshots from earlier local paths in old docs are historical. **Current path** is within Herd.

## Merge / GO boundaries

- [x] Documentation-only diff and relative link audit; no source modification.
- [x] Decisions and first task scope reconciled.
- [x] Owner explicitly authorized **`MERGE PR #1`**; fresh diff/mergeability rechecked and GitHub completed merge successfully.
- [x] Local `main` synced to governance merge SHA, worktree clean. [21B](21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md) records approved merge anchor and is **READY_FOR_OWNER_GO**. Codex will capture actual clean main branch-point SHA on named GO.
- [ ] Owner separately issues **`GO CODING ICH-S0-01`**. Codex changes code ONLY then on feature branch.
- [ ] Future DB/schema tasks `ICH-S0-02` require verified isolated test DB; cannot be automatically authorized with first task.

**Governance merge now complete**, but this document still provides **NO coding, release, deployment or database authorization**. Such actions need separate explicit GO.
