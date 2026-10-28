# ICHES 2027 — AI Agent Workspace

> **CURRENT LOCAL PATH (owner-directed relocation, 28 Oct 2026):** `D:\PINJAM-AFNAN\Herd\iches-unisya`. The repository was moved intact from `D:\PINJAM-AFNAN\iches-unisya`; the old directory no longer exists. Herd Free `herd links` confirms `http://iches-unisya.test/` points to the **new** path using PHP 8.4. Git remote `akhmadafnan/iches-unisya`, branch `docs/19e-agent-workspace`, HEAD `1403178c38c131bd81ab8ee48a204b5bdec2d494`, clean after move. VS Code was opened again targeting the new directory. Older references to the old path in snapshot sections are **historical** and must not be used as the current workspace. No database, source code, migrations or other Herd links were altered. **NO GO CODING.**

> **UPDATE 28 OCT 2026 — SETUP AUTHORIZED & COMPLETED; NO GO CODING.** On connected Windows desktop the owner-authorized canonical GitHub clone now exists at `D:\PINJAM-AFNAN\iches-unisya` (hyphen), remote `akhmadafnan/iches-unisya`, clean branch `docs/19e-agent-workspace` tracking origin, initial checkout SHA `432dcd012fa49b25019ad6464e7dc3759b99cd11`. The chosen VS Code at `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (space), v1.143.1, **opened the ICHES workspace**. OpenAI Codex extension `openai.chatgpt@26.51021.22027` is installed in the shared Windows user profile; **in-app sign-in/Skills loading NOT VERIFIED**. Four repo Skills plus AGENTS/PLANS verified. Git clone and GUI launch were done with user setup approval, **not application coding GO**. PR #1 is still draft/unmerged, and disposable MySQL DB plus technical decisions are pending. Earlier lines describing the target folder as absent or GUI as unlaunched are pre-setup historical observations.

> **Mulai di sini.** Repository ini masih **blueprint**, belum aplikasi Laravel yang berjalan. **Belum ada GO CODING.** Setelah GO Task ID atau bounded Sprint GO eksplisit, Codex VS Code mengubah kode serta mencatat evidence terbatas; ChatGPT mengelola keputusan, spesifikasi, dokumentasi, review, dan GitHub.

## Governance PR merged — first task ready, NO GO CODING

- PR #1 merged after explicit owner authorization: approved governance SHA `275cfbfd0e431033eb05d2c519eda19282465962`. Local folder `D:\PINJAM-AFNAN\Herd\iches-unisya` has synced `main`, Git clean.
- [21B first Task Work Order](docs/21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md) now **READY_FOR_OWNER_GO** for DB-independent Laravel 13/Inertia/Vue3/TS/Tailwind4. Owner must separately say `GO CODING ICH-S0-01` to permit Codex coding; no Sprint/DB/auth authorization implied.

## Stage 21 — current next action

- [21A audit / merge gate](docs/21A-PR1-GOVERNANCE-REVIEW.md): docs-only PR reviewed, 263 local Markdown links checked, zero broken. **No owner merge approval yet**.
- [21B single-task Codex Work Order](docs/21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md): `ICH-S0-01` database-independent Laravel/Inertia/Vue/TS/Tailwind4 inside `D:\PINJAM-AFNAN\Herd\iches-unisya`, pending PR merge + explicit named GO.
- [21C acceptance](docs/21C-S0-01-ACCEPTANCE-AND-HANDOFF.md): actual Herd HTTP 200, PHP DB-free smoke, Vite build/typecheck and safe diff before acceptance.


## 21K-06 — Dev-only demo seeder convention locked (CURRENT)

- Owner simplified local development: all synthetic seeded user emails end in `@iches.test`; where a local-only demo password login is supported, every demo credential is the literal `password`, hashed at rest. See [21K-06 final fixtures policy](docs/21K-06-DEV-DEMO-USER-SEED-POLICY.md).
- The merged S0-02 users schema has **no password column**, and real participant login remains passwordless OTP/Magic Link. Do not add participant password requirements or seed weak accounts to production. Demo paths/seeding require local-only opt-in and never run in production. Use non-destructive idempotent seeding.
- This is a decisions/docs-only update, **not GO CODING ICH-S0-03** or permission for schema changes, DB writes/reset, push, PR, merge or deploy.

## 21K-05 — ICH-S0-02 MERGED / COMPLETE (HISTORICAL)

- Owner expressly approved SHA-pinned standard merge of [PR #6](https://github.com/akhmadafnan/iches-unisya/pull/6), feature `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`; GitHub returned `merged=true`, merge commit `3eb1be7b23a2fd08fdcbd84e5fd1ba0819f90201` verified as `main` HEAD. Exactly **14** reviewed paths, two feature commits.
- [21K-05 postmerge evidence](docs/21K-05-S0-02-MERGED-COMPLETE.md): real push and PR GitHub Actions PHP 8.4/Node22 both SUCCESS, local MySQL S0-02 8/134 PASS, no CI MySQL service. Main branch unprotected/no required checks or active rulesets; normal merge without bypass.
- S0-01, S0-07 and S0-02 now COMPLETE. Next ICH-S0-03 passwordless OTP and S0-04 RBAC **NOT AUTHORIZED**; no DB migration/reset, other coding or deployment. Independent Skills PR #3 open and unmerged.

## 21K-04 — S0-02 PR #6 CI PASS, owner merge authorization pending (HISTORICAL)

- [PR #6](https://github.com/akhmadafnan/iches-unisya/pull/6) **OPEN / UNMERGED**, branch `feat/ich-s0-02-db-foundation` published at exact Owner-approved SHA `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`, 2 commits, 14 scoped files; GitHub `mergeable=true`, `mergeable_state=clean`.
- [21K-04 live Actions evidence](docs/21K-04-S0-02-PR6-LINUX-CI-PASS-AND-MERGE-GATE.md): push run 40695877645 **SUCCESS**, PR run 40696398403 **SUCCESS**, real PHP8.4.27/Composer/Laravel4 tests-36 assertions/Pint29/Node22/npm/TS/Vite all green. Local MySQL 8 tests/134 assertions independently rerun in [21K-02](docs/21K-02-S0-02-REV03-FINAL-LOCAL-REVIEW.md), NOT run in Linux DB-free CI.
- **REVIEW_PASS / CI_RUNTIME_PASS / OWNER_MERGE_APPROVAL_PENDING.** Only after separate explicit owner merge GO may PR #6 be merged following fresh SHA/files/required-check verification. No DB writes/reset, new coding or deployment; PR #3 unaffected.

## 21K-03 — S0-02 accepted, exact Codex feature push authorized (HISTORICAL)

- Owner formally **ACCEPTED REV-03** and authorized Codex VS Code's normal non-force push of EXACT `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb` on `feat/ich-s0-02-db-foundation`. [21K-03 handoff](docs/21K-03-S0-02-OWNER-ACCEPT-CODEX-PUSH-HANDOFF.md). On successful push verification, ChatGPT may create PR and inspect Ubuntu PHP8.4/Node22 Actions, not merge.
- Independent local preflight still HEAD `f4eb25c`, parent `78bc6d9`, worktree clean; new feature branch **not yet present** on GitHub. Do not incorporate newer governance-only `main` commits into feature before exact-SHA push; no rebase, merge, amend or reset.
- No DB writes or changes allowed by publication authorization, no reset, source changes, merge, new coding task or deploy.

## 21K-02 — S0-02 REV-03 local review PASS, owner push approval needed (HISTORICAL)

- [21K-02 full review](docs/21K-02-S0-02-REV03-FINAL-LOCAL-REVIEW.md): exact local `feat/ich-s0-02-db-foundation` HEAD `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`, parent `78bc6d9`, clean; REV-03 3 scoped paths +97/-0, untouched original migration.
- Independently reran **8 DB tests/134 assertions** and **4 DB-free tests/36 assertions**, Pint 29 files, Composer, TS and Vite PASS; DB batch 1+2 Ran and generated active-only uniqueness/actual empty domain tables verified in MySQL `iches_unisya`. REV-03 issue resolved, but `down()` intentionally disallows unsafe rollback.
- **REVIEW_PASS locally / owner publication permission pending**. No feature push/PR/CI Linux, merge, production DB action, wipe, deployment or new coding GO. Owner may authorize Codex normal push of exact `f4eb25c...`; ChatGPT verifies remote/PR/CI only after published.

## 21K-01 — S0-02 audited, bounded REV-03 required (HISTORICAL)

- [21K-01 independent audit report](docs/21K-01-S0-02-INDEPENDENT-REVIEW-REV03.md): local commit `78bc6d9a904c86b334c28f645d908452295ba26e`, 13 scoped files, clean. Re-run PHP 4/36 PASS, Pint 28 PASS, Composer PASS, npm typecheck/Vite build PASS. Read-only MySQL confirms correct `iches_unisya`, InnoDB, FKs, empty domain tables after Codex synthetic test rollback.
- **REV-03 REQUIRED:** `role_assignments_scope_unique` still applies to revoked rows, preventing later new grant of same role/scope. Codex-only same S0-02 scope: a **new forward-only correction migration** implementing uniqueness of active grants, regression test revoke→regrant and preserved revocation history, rerun evidence, append log, new local commit. No old migration rewriting, destructive reset, push/PR/merge/deploy.
- Existing S0-02 owner GO remains sufficient for this correction. Linux CI for new commit still NOT_RUN; do not mark S0-02 accepted until verified.

## 21J-07 — MySQL authenticated and Codex S0-02 READY TO RESUME (HISTORICAL)

- Owner edited local ignored `.env`; independent read-only PDO probe connected to **`iches_unisya`**, MySQL 9.7.1, InnoDB, FK checks on, **0 tables**, exit 0. No credential printed, no DB writes, Git clean. Old 1045 blocker resolved.
- **GO CODING ICH-S0-02 remains active**; [21J-05 execution contract](docs/21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md) includes verified SQL evidence and selective UUIDv7 policy. Resume Codex on clean `feat/ich-s0-02-db-foundation`; safely fast-forward current docs-only `origin/main` when possible, do scoped Laravel MySQL config, UUIDv7 entity foundation and additive `migrate`, non-destructive tests, then local commit. No extra GO needed.
- Do not print or disclose `.env`, drop/reset tables, push, merge or deploy.

## 21J-06 — S0-02 temporarily blocked by MySQL 1045 (HISTORICAL)

- Codex safely created `feat/ich-s0-02-db-foundation` (clean) from synced main `19ab3e5`; attempted read-only MySQL auth on `iches_unisya` with local root, **1045 / exit 1**. NO source changes, migrations or local commit. Owner must configure correct credentials directly in ignored local `.env`; report readiness without password. GO S0-02 remains valid.
- [21J-05 GO contract](docs/21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md) now clarifies **UUIDv7 only for primary keys of independent canonical domain entities**, matching UUID FKs; plain pivot composites, codes, counters, framework IDs are not blanket UUIDv7. Codex resumes same feature branch after auth and verifies selected schema before migrations.

## 21J-04/05 — ICH-S0-02 OWNER GO / CODEX EXECUTION (CURRENT)

- Owner reports local schema `iches_unisya` (underscore) exists and explicitly grants `GO CODING ICH-S0-02`. [Latest correction 21J-04](docs/21J-04-ICHES-UNDERSCORE-DB-AND-S0-02-GO.md); [bounded Codex handoff 21J-05](docs/21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md). Earlier `iches-unisya` spelling is superseded.
- Codex VS Code only: safely update main, create `feat/ich-s0-02-db-foundation`, verify actual MySQL connection selects exact `iches_unisya`, wire Laravel MySQL/ignored local credentials, implement UUIDv7 identity/event foundations and scoped nondestructive migrations/tests; preserve DB-free CI; stop after local commit.
- Owner's creation report is **not** conclusive SQL authenticated proof; first Codex operation checks. No new GO needed for S0-02 source and additive migration; no reset, push/PR/merge/deploy.

## 21J-02 / 21J-03 — Database name fixed; S0-02 next FAST track (HISTORICAL)

- **RD-03C owner decision:** use exactly `iches-unisya` (hyphen), not `iches_unisya`; owner creates manually in phpMyAdmin. Temporary local MySQL root allowed for development with password only in ignored `.env`; never publish credentials or use root in production. [21J-02 decision](docs/21J-02-ICHES-DEV-DB-NAME-AND-LOCAL-ACCESS.md).
- [21J-03 concise S0-02 Work Order](docs/21J-03-ICH-S0-02-FAST-TRACK-WORK-ORDER.md) prepared. The DB has not been verified or created by ChatGPT; existing Laravel DB connections still disabled. After owner confirms creation, a separate **GO CODING ICH-S0-02** is required. Codex-only coding, no automatic destructive reset, CI remains DB-free.
- Accelerate practical development and defer detailed production hardening to a release gate; do not bypass basic secret safety, database isolation, or owner authorization for destructive SQL. S0-01/S0-07 complete.

## 21J-01 — OWNER-LOCKED: one primary development database (CURRENT)

- [21J-01 definitive owner decision](docs/21J-01-SINGLE-PRIMARY-DEV-DATABASE-DECISION.md): **RD-03B** replaces former compulsory isolated `iches_unisya_test` database. One dedicated local ICHES `iches_unisya` schema will serve development/manual synthetic trials, with local scoped credentials and no impact on any other application database.
- **Not provisioned/verified:** schema existence, authenticated login, MySQL InnoDB/FK, account privileges, Laravel DB configuration still pending. The merged scaffold has `DB_CONNECTION=disabled`; CI remains DB-free. No database creation, migration, coding, destructive reset or deployment authorized.
- Normal development migrations only after `ICH-S0-02` named GO; never automatically drop data using `migrate:fresh` against the primary dev schema. A later production cleanup/reset is a separately approved, backed-up cutover decision. Existing 20E test DB instructions are archived historical policy.
- S0-01 & S0-07 remain COMPLETE. PR #3 design Skills still separately OPEN.

## 21I-11 — ICH-S0-07 MERGED and COMPLETE (CURRENT)

- Owner-authorized ordinary merge of [PR #5](https://github.com/akhmadafnan/iches-unisya/pull/5) succeeded with feature HEAD `294a54b223676df3b6e9124480e271ee3a50bdc7`, merge `f6d5bed4518e40e3b6d9da44a27661147571b478`; three reviewed files only, no bypass, migration, deployment or new code.
- [21I-11 merged/CI record](docs/21I-11-S0-07-MERGED-AND-NEXT-GATE.md): real GitHub Actions PR run PHP 8.4.27, 4/36 tests, Pint, Node 22/TS/Vite success. Duplicate push event cancelled; required checks off, active rulesets none.
- S0-01 and S0-07 COMPLETE; S0-02 dedicated MySQL test DB remains unverified and NO_GO; future coding requires explicit new named authorization. PR #3 Skills still unmerged.

## 21I-10 — S0-07 PR #5 CI runtime PASS, pending owner merge (CURRENT)

- Owner-approved corrected `feat/ich-s0-07-ci` SHA `294a54b223676df3b6e9124480e271ee3a50bdc7` pushed and GitHub remote/PR verified identical. Exactly 3 scoped changed files; PR #5 OPEN and unmerged.
- [21I-10 live CI evidence](docs/21I-10-S0-07-LIVE-CI-PASS-AND-MERGE-GATE.md): [PR workflow success](https://github.com/akhmadafnan/iches-unisya/actions/runs/40678863079), PHP 8.4.27, 4/36 PHPUnit, Pint 18 files, Node22/npm/TS/Vite all SUCCESS. [Duplicate push workflow](https://github.com/akhmadafnan/iches-unisya/actions/runs/40678855656) CANCELLED, not PASS, due to concurrency policy. GitHub `mergeable=true`, `mergeable_state=unstable` despite successful PR job; branch required check rules must be respected.
- **REV-02 CLOSED / LOCAL and LINUX CI REVIEW_PASS / OWNER_MERGE_APPROVAL_PENDING.** No other code, DB, migration, deployment or PR #3 merge authorized.

## 21I-09 — REV-02 accepted and exact Codex push authorized (CURRENT)

- Owner has explicitly authorized Codex VS Code to push **only** `feat/ich-s0-07-ci` at `294a54b223676df3b6e9124480e271ee3a50bdc7` with ordinary no-force push. [21I-09 publication handoff](docs/21I-09-S0-07-REV02-PUSH-AUTHORIZATION.md).
- Local branch and clean status independently verified; remote `origin/feat/ich-s0-07-ci` remains `c420d951...` until push succeeds. ChatGPT did **not** perform the push and does not claim a new CI run. After Codex confirmation, verify remote SHA and actual PR #5 Ubuntu/PHP8.4/Node22 checks. Merge separately owner-approved.

## 21I-08 — REV-02 local review PASS, corrected SHA awaits owner push approval (CURRENT)

- `ICH-S0-07` branch `feat/ich-s0-07-ci` local HEAD `294a54b223676df3b6e9124480e271ee3a50bdc7` (parent old remote `c420d951`), clean. Scoped revision: test-only Inertia page path aligned to lowercase `js/pages` with negative missing-page test; temporary `.env.example` copied only on CI runner; build log append-only +28. [21I-08 independently rerun checks](docs/21I-08-S0-07-REV02-FINAL-LOCAL-REVIEW.md): 4 PHP tests/36 assertions, Pint, Composer and diff PASS.
- **REV-02 local REVIEW_PASS**. PR #5 remote still at old failed `c420d951`. **Owner has not approved pushing new SHA**, so Linux PHP8.4/Node22 checks remain NOT_RUN after fix; no merge, DB or deployment.

## 21I-07 — PR #5 Linux CI FAILED; REV-02 (CURRENT)

- [PR #5](https://github.com/akhmadafnan/iches-unisya/pull/5) open, exact 2-file diff, head `c420d951...`, **no merge**. Two actual Ubuntu CI runs (push + PR) FAILED at PHPUnit after PHP/Composer PASS; Node22/Pint/npm/TS/build steps **SKIPPED**, not green.
- Cause: Inertia testing default `resource_path('js/Pages')` but app component actually `resources/js/pages/Foundation.vue`; Linux case-sensitive. Keep file existence assertions, align test path, diagnose pre-build Vite manifest warnings. [21I-07 evidence and REV-02 scope](docs/21I-07-S0-07-LINUX-CI-FAILURE-REV02.md), comment attached to PR.
- Existing S0-07 owner GO covers bounded CI-specific correction by Codex only; **no automatic push of new SHA** (prior authorization pinned old `c420d951`), no merge, DB, other task or deployment.

## 21I-06 — Owner accepted S0-07; exact Codex push authorized (CURRENT)

- Explicit owner acceptance and bounded publication approval for `feat/ich-s0-07-ci` HEAD `c420d95175d7d25cd9fee6719180acaf0b684903`. Codex VS Code performs push; ChatGPT GitHub Connector creates PR **only after verified remote push** and checks Ubuntu CI; merge still owner-gated.
- [21I-06 push handoff](docs/21I-06-S0-07-OWNER-ACCEPT-PUSH-PR-HANDOFF.md). Latest preflight local clean/GCM active, remote feature branch not present. No extra coding GO, DB/migration or deployment.


## 21I-05 — CI review correction closed (CURRENT)

- Local `feat/ich-s0-07-ci` final SHA `c420d95175d7d25cd9fee6719180acaf0b684903` (parent `17e3350`), clean; exact workflow one-line pin corrected, append-only log +21 lines. [21I-05 independent final review](docs/21I-05-ICH-S0-07-FINAL-REVIEW.md) marks **REVIEW_PASS / OWNER_ACCEPTANCE_PENDING** and REV-01 closed.
- Existing owner GO is sufficient for implemented CI code and narrow fix. **No publication authorization yet**. No remote CI runs, so Linux/Node22 status remains `NOT_RUN`. Await owner ACCEPT and Codex push permission, then GitHub connector PR, run actual checks before separate merge authorization.

## 21I-04 — Local CI implementation independently reviewed (CURRENT)

- `ICH-S0-07` local commit `17e335049f209110e76a03dc99bc588cc7cee925` (branch `feat/ich-s0-07-ci`, base `42a8633`) independently verified clean, 2 changed files only. Local tests reported PASS, **GitHub CI / Node 22 NOT_RUN**.
- [21I-04 independent code review](docs/21I-04-ICH-S0-07-INDEPENDENT-CODE-REVIEW.md) **REVISIONS_REQUIRED**: fix `shivammathur/setup-php` annotated `v2` tag-object SHA to the actual commit SHA `eb7c497e18156a6bbabfef1d3a82760b9eda3962` (verified via GitHub Git API); other two pins are true commits.
- Codex may make this **single task-bounded correction under existing S0-07 GO** on the same local branch, append corrected build-log evidence and a separate follow-up local commit. No push/PR/merge authority; no additional source tasks, DB, migrations, CMS or deploy authorized.

## 21I-03 — GO CODING ICH-S0-07 granted (CURRENT)

- Owner explicitly authorized one task: **ICH-S0-07 GitHub Actions DB-free CI** on 28 Oct 2026. Scope set by [21I-02 Work Order](docs/21I-02-ICH-S0-07-CI-WORK-ORDER.md), [21I-03 Codex execution handoff](docs/21I-03-GO-ICH-S0-07-CODEX-EXECUTION.md). Status `GO_AUTHORIZED / AWAITING_CODEX_EXECUTION`, **not implemented yet**.
- Only Codex VS Code writes CI/test application files. Local accepted feature branch still clean at `8eba7cb5`; local main is old and must be fetched and safely fast-forwarded before a new task branch. ChatGPT governance/doc edits do not constitute CI implementation.
- No other code GO, DB/migration, source branch push/PR/merge or deployment authorization implied.

## 21I — ICH-S0-01 MERGED and next bounded Work Order (CURRENT)

- [PR #4](https://github.com/akhmadafnan/iches-unisya/pull/4) merged with explicit owner approval: accepted feature commit `8eba7cb5b9da0577910beb919844165869591e05`, merge `561baf322d459d898f5f96d280f5a5f02634c59f`. **No GitHub Actions CI yet**; local audit 21E PASS.
- [21I-01 postmerge execution path](docs/21I-01-ICH-S0-01-MERGE-AND-48H-NEXT-PATH.md); [21I-02 task packet](docs/21I-02-ICH-S0-07-CI-WORK-ORDER.md) `READY_FOR_OWNER_GO / NO_GO`. S0-02 isolated MySQL test database not verified; other code tasks, migrations and deployment not authorized.
- `main` remote advanced; local feature branch must be preserved. Next authorized Codex task must inspect and fast-forward local `main` safely before creating a **new** feature branch. 21H Skills [PR #3](https://github.com/akhmadafnan/iches-unisya/pull/3) still separate/unmerged.

## 21G-06 — ACTUAL CODEX READ-ONLY REPORT VALIDATED (current)

- [21G-06 review](docs/21G-06-CODEX-READONLY-VALIDATION-REVIEW.md): explicit Codex reading 5/5 PASS; five routing simulations and three permission-denial simulations PASS; ICHES native precedence upheld. Reviewer independently rechecked detached QA HEAD `80b888e`, clean staged/unstaged diff, five valid Skill names and untouched Laravel feature HEAD `8eba7cb`.
- **Automatic Skill activation remains NOT_TESTED**. W1 suitable for reference use under future named GO only; no further application code or merge authorization. Next is **21H design Skills read-only audit**, plus separate existing Git credential blocker.


## 21G — PR #2 merged, static QA PASS; Codex test response pending (latest)

- Owner explicitly authorized merge of docs/Skills-only PR #2, merged SHA `e5362f919fbd7ce23fdd50761b5dc99fa9410e18`. Five third-party Skills (Ponytail general/review/audit; Matt diagnosing-bugs/TDD) now on `main`.
- Safe detached worktree `D:\PINJAM-AFNAN\ICHES-SKILLS-READONLY` was created for **read-only Codex test**; original local Laravel feature branch `feat/ich-s0-01-foundation` and HEAD `8eba7cb5b9da0577910beb919844165869591e05` unchanged and clean.
- [21G-05 post-merge test handoff](docs/21G-05-POST-MERGE-CODEX-READONLY-TEST.md): static verification of all 5 Skill YAML names PASS; model invocation and auto-trigger **NOT_TESTED** until Codex returns its actual output. No new GO Coding.


## 21G — External Skills curation, separate unmerged docs branch

- Owner approved selective external Skills integration. Initial static W1 copies are staged on remote **`docs/21g-skills-integration`**, not on local unpushed Laravel feature branch. Five skills: Ponytail, Ponytail Review/Audit, Matt diagnosing-bugs, Matt TDD; source SHAs and MIT licenses retained.
- [21G routing](docs/21G-01-SKILLS-ROUTING-POLICY.md), [source provenance](docs/21G-02-SKILLS-PROVENANCE-AND-LICENSES.md), [activation & future waves](docs/21G-03-ACTIVATION-AND-WAVE-PLAN.md). No automatic installation, scripts, project source edits or owner PR merge approval. W2/W3 pending review.
- Accepted `ICH-S0-01` feature branch push remains blocked by local GitHub authentication; do not conflate the 21G docs PR with the accepted application PR.


## 21F — ICH-S0-01 owner accepted; Codex push/PR only

- Owner explicitly accepted independent 21E review of local feature commit `8eba7cb5b9da0577910beb919844165869591e05` and authorized **Codex VS Code** to push only `feat/ich-s0-01-foundation` and create PR against `main`.
- [21F execution handoff](docs/21F-ICH-S0-01-OWNER-ACCEPT-PUSH-PR-HANDOFF.md): do not reset/rebase/change accepted code; verify clean branch and diff, push without force and open PR (via authenticated GitHub UI; `gh` not found in PATH). **No merge, DB/migration, CMS, auth, deployment or new-task GO.**
- Feature branch remains local/unpushed at last verification; 19F is the single current status board.

## 21E — ICH-S0-01 code review completed; owner acceptance pending

- [21E independent code review](docs/21E-ICH-S0-01-INDEPENDENT-CODE-REVIEW.md): **REVIEW_PASS**, local-only feature commit `8eba7cb5b9da0577910beb919844165869591e05`. PHP, Composer, Vite, TS, Pint, Herd HTTP, negative URL/scope checks independently passed. Minor empty favicon not blocking.
- **No push, feature PR, owner acceptance, merge, DB migration or deployment**. Owner decides whether to accept and authorize Codex to push/open PR. 19F remains the sole current operational board.

## 21D — CURRENT AUTHORIZATION (overrides older historical NO_GO snapshots)

**Owner explicitly authorized only `GO CODING ICH-S0-01` on 28 October 2026.** [21D Codex GO prompt](docs/21D-ICH-S0-01-GO-CODEX-EXECUTION.md), [21B Work Order](docs/21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md), and [19F current board](docs/19F-CURRENT-SPRINT-BOARD.md) are authoritative for this specific Task. Codex VS Code is the **only application code writer**. Current local repo `D:\PINJAM-AFNAN\Herd\iches-unisya` must be synced to clean current `main` before Codex branches. **No GO** for S0-02/07, MySQL migrations, auth, CMS, PR auto merge or deployment. Historic “no named GO” statements in old snapshots predate the owner's new explicit GO.


## One Master Plan (DRAFT — no GO)

- [PLANS.md](PLANS.md) — one M0–M9 execution roadmap, NOT a second status board.
- [20A](docs/20A-MASTER-PLAN-SKILLS-AND-HARNESS.md) and [20B Governance](docs/20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md) — bounded sprint model, 4 Skills, controlled evidence; NO named GO.
- [20C Local Readiness](docs/20C-LOCAL-PREFLIGHT-AND-GATES.md) — verified Windows versions and open toolchain gaps; [Sprint-01 draft](docs/20C-01-DRAFT-SPRINT-01-WORK-ORDER.md) — three bounded foundation tasks, **not authorized yet**.
- [harness/README.md](harness/README.md) — Codex append-only evidence **only after named GO CODING**.

## Empat berkas kerja harian (bukan 50+ blueprint)
1. **[WORKSPACE.md](WORKSPACE.md)** — pintu masuk dan urutan baca (berkas ini).
2. **[docs/19F-CURRENT-SPRINT-BOARD.md](docs/19F-CURRENT-SPRINT-BOARD.md)** — **satu** status tugas aktif, blocker, dan next action; jangan menciptakan status tandingan di chat.
3. **[docs/19E-AGENT-WORKSPACE-RUNBOOK.md](docs/19E-AGENT-WORKSPACE-RUNBOOK.md)** — kontrak operasional, stop conditions, handoff, dan timebox.
4. **[AGENTS.md](AGENTS.md)** — instruksi lintas tugas untuk Codex VS Code, termasuk larangan edit dokumentasi.

## Sumber otoritatif menurut kegunaan
| Kebutuhan | Buka |
|---|---|
| Keputusan produk yang disetujui, masih draft, atau open | [Register keputusan](docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md) |
| Siapa mengerjakan apa / aturan Git | [19A protokol](docs/19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md) |
| Seluruh daftar task dan dependensi | [19B backlog](docs/19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) |
| Format work order satu task untuk Codex | [19C template dan contoh](docs/19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) |
| Peta blueprint → perubahan kode → bukti test | [19D traceability](docs/19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md) |
| Aturan implementation agent | [.agents/skills/iches-codex-engineering/SKILL.md](.agents/skills/iches-codex-engineering/SKILL.md) |
| Aturan frontend / CMS (hanya task yang relevan) | [.agents/skills/iches-public-frontend/SKILL.md](.agents/skills/iches-public-frontend/SKILL.md) |

## Prosedur singkat
**Sebelum GO:** owner dan ChatGPT menutup decision gate, menguji kesiapan lingkungan secara read-only, dan mengisi satu work order. **Jangan menjalankan coding hanya dari roadmap**.

**Setelah named GO Task/Sprint:** owner membuka repo target di VS Code → Codex membaca `AGENTS.md`, board, work order dan 2–4 blueprint relevan per task → kerja di `feat/...` (bukan `main`) → tes dan append-only log → lanjut hanya ke task sprint yang tercantum dan lulus gate → ChatGPT review → owner putuskan merge. **Berhenti** bila muncul keputusan yang belum disetujui, data/secrets nyata, atau scope melebar.

**Prioritas paling dekat:** foundation minimal → website publik + CMS + countdown → Abstract Text+PDF. Modul lain tetap ada di backlog, tetapi **tidak boleh ditarik ke task aktif hanya agar terlihat 'selesai'**.

**Tentang target dua hari:** [19E](docs/19E-AGENT-WORKSPACE-RUNBOOK.md) memberi timebox 48 jam **setelah keputusan dan lingkungan siap**; keberhasilan berarti hasil yang dapat didemokan/dites, bukan klaim bahwa S0–S9 selesai.

## Herd Free + phpMyAdmin preferred workflow

- [20E-02 Herd Free + phpMyAdmin](docs/20E-02-HERD-FREE-PHPMYADMIN-WORKFLOW.md) is now the default: `http://iches-unisya.test/` linked to repo via Herd Free, PHP 8.4; `https://phpmyadmin.test/index.php` HTTP 200. Project currently docs-only so returns 404 until GO Coding.
- Test-only DB/user **not created or authenticated**; use the phpMyAdmin GUI, not a temporary CLI administrator login-path unless separately requested. No GO Coding.

## 20E temporary administrator login required

- User approved provisioning isolated `iches_unisya_test` + `iches_test` only; **admin login blocked (MySQL 1045)** in two read-only checks, no DDL executed.
- [20E-01 local login setup](docs/20E-01-TEMPORARY-MYSQL-ADMIN-LOGIN.md): one user-entered password prompt in VS Code, never send credentials to chat. **NO GO Coding.**

## 20E isolated MySQL test database readiness

- [20E Readiness Runbook](docs/20E-MYSQL-ISOLATED-TEST-DB-READINESS.md): MySQL 9.7.1 native client found, Running server, localhost handshake verified. **Test-only account/schema authentication not verified; no SQL or DB writes.**
- RD-01..06 remain FINAL. **No GO CODING**; next is separately authorized test-only DB provisioning and authenticated read-only check.

## PRE-03 VERIFIED / PRE-02 OWNER APPROVED

- Codex read-only handshake **PASS**: four Skills directly readable, canonical remote, docs branch, clean worktree. Automatic Skill invocation not tested.
- MySQL97 Windows service **9.7.1** running; TCP port 3306 reachable, but authenticated isolated test DB **not verified**.
- [20D technical decision packet](docs/20D-PRE-02-TECHNICAL-DECISION-PACKET.md): **RD-01–RD-06 FINAL owner-approved**, including participant passwordless OTP/Magic Link. Authenticated isolated MySQL test DB still needed; **NO GO Coding**.


## Codex readiness check

- Owner screenshot confirms visible VS Code **Codex panel and chat history** in the ICHES workspace.
- [20C-02 READ-ONLY handshake](docs/20C-02-CODEX-READ-ONLY-HANDSHAKE.md) is the next action: verify all four Skills can be read by the running agent; **NO GO CODING**.

## Lokasi kerja lokal disepakati

- **Windows CURRENT:** `D:\PINJAM-AFNAN\Herd\iches-unisya` — **sudah dipindahkan**, branch `docs/19e-agent-workspace`, bersih; GitHub remote dan 4 Skills tetap tersedia. Lokasi lama `D:\PINJAM-AFNAN\iches-unisya` sudah tidak ada.
- **VS Code utama pilihan owner:** `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (**spasi**, v1.143.1), GUI sudah membuka ICHES. OpenAI Codex extension terpasang di profil pengguna; login/Skills invocation belum diverifikasi.

## Status verifikasi awal (28 Oktober 2026)
- Target: `akhmadafnan/iches-unisya`, `main`; source aplikasi target **belum ada** saat audit.
- Repo lama `akhmadafnan/international-conference-platform` adalah referensi **selective reuse**, bukan kode yang boleh langsung disalin.
- Scope menulis code: **Codex VS Code** setelah GO; perubahan dokumen: **ChatGPT**.
- Branch docs ini adalah usulan tata kelola; **tidak memberikan GO CODING**.

> Saat chat baru: baca `WORKSPACE.md` + 19F; saat Codex: berikan **work order Task/Sprint bernama yang sudah mendapat GO aktual**, bukan semua blueprint.
