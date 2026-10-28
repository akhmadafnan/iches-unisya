# 19F — Current Sprint Board & Handoff Ledger

> **LATEST — 28 OCT 2026 / ICH-S0-04 PR #10 NORMAL MERGE SUCCESS / COMPLETE:** Owner separately approved normal merge only after final verification. Source `7edd837eb7285b3dbed0dbeacc2eb5546d76e960`, **exact 16 files**, 1 feature commit; [GitHub Actions #40716707863](https://github.com/akhmadafnan/iches-unisya/actions/runs/40716707863) **SUCCESS** (PHP8.4, DB-free tests, Pint, Node22 TypeScript/build), PR `mergeable=true` / `clean`, no active required checks or rulesets. Called GitHub `merge_method=merge` with `expected_head_sha` guard, **merged=true**, merge SHA **`1942db7def41a1c839d2bf86936880ad689ed178`**; GitHub postmerge confirms PR CLOSED/MERGED and main at same SHA before documentation commits; merge parents **`cfc12603850112a3c6e95e99518a3caf6a40e203`** and exact authorized source. [21L-03 final report](21L-03-S0-04-MERGED-COMPLETE.md). **No app code edits by ChatGPT, new migrations, DB reset, deployment, PR #3 merge or next coding task**. ICH-S0-04 **MERGED/COMPLETE**, Issue #9 completed. Separate future task requires fresh GO. Whole repo Windows Pint had 14 preexisting CRLF findings; Linux GitHub Pint PASS. Live authenticated browser E2E remains deferred.

> **LATEST — 28 OCT 2026 / ICH-S0-04 PR #10 OPEN / REMOTE CI SUCCESS / MERGE BLOCKED UNTIL OWNER GO:** Owner explicitly gave GO PUSH & PR, not merge. Exact local reviewed SHA `7edd837eb7285b3dbed0dbeacc2eb5546d76e960`, parent `08753fa9dccdeef2a4c0fa4314f85d07b262d71e`, 16 changed paths and clean tree verified; push **normal/no force** exit 0 and remote SHA matched. [PR #10](https://github.com/akhmadafnan/iches-unisya/pull/10) created toward main: **OPEN / NOT MERGED, 1 commit, 16 files, mergeable=true, mergeable_state=clean**. [GitHub Actions #40716707863](https://github.com/akhmadafnan/iches-unisya/actions/runs/40716707863) **COMPLETED SUCCESS**, all PHP8.4/Node22 steps (DB-free tests, Pint, Composer/npm locks, TypeScript/build) PASS; whole-repo Pint old Windows 14-line-ending failures were not reproduced by Linux CI. Local transaction-based MySQL 21/408 and DB-free 12/131 were independently verified previously; **real authenticated browser E2E not yet tested**. Branch main currently no enforced required checks/rulesets; owner final merge authorization still mandatory. No source coding, database reset/migration, deployment or PR #3 merge was performed in this stage. Audit [21L-02](21L-02-S0-04-INDEPENDENT-LOCAL-AUDIT.md), publication note on PR #10 and Issue #9. Next gate: distinct GO MERGE PR #10 ICH-S0-04 with fresh SHA/CI/rules checks.

> **LATEST — 28 OCT 2026 / ICH-S0-04 REVIEW_PASS_LOCAL, PENDING OWNER PUSH/PR:** Codex VS Code committed scoped authorization and Switch Workspace on local branch `feat/ich-s0-04-scoped-workspaces`, SHA **`7edd837eb7285b3dbed0dbeacc2eb5546d76e960`**, parent **`08753fa9dccdeef2a4c0fa4314f85d07b262d71e`**; 16 paths, clean worktree, no migration. [Independent ChatGPT audit 21L-02](21L-02-S0-04-INDEPENDENT-LOCAL-AUDIT.md) reviewed source and reran **DB-free 12/131 PASS; transactional MySQL 21/408 PASS; targeted workspace 3/100 PASS; Composer, scoped Pint 9 files, TypeScript/Vite build, diff check PASS**. Whole-repo Pint locally **FAIL 14 old Windows formatting issues**, not hidden; browser demo is **synthetic Inertia**, real auth-backed browser session not yet verified. **No remote CI, push, PR, merge, DB reset, schema change or deployment**. Next possible step requires owner **GO PUSH & PR ICH-S0-04** for exact SHA, non-force, followed by real PHP8.4/Node22 Actions review. Do not use Codex again solely for publication; brand/font rules remain locked.

> **LATEST — 28 OCTOBER 2026 / OWNER GO CODING ICH-S0-04 (SCOPED RBAC + SWITCH WORKSPACE):** Following ICH-S0-03 [PR #8](https://github.com/akhmadafnan/iches-unisya/pull/8) normal merge (merge SHA `3df38cf4229e3aec011a2d20fee54984d6560ccc`), the owner explicitly instructed execution of the next named phase. [Work Order 21L-01](21L-01-S0-04-SCOPED-RBAC-WORK-ORDER.md) and [Issue #9](https://github.com/akhmadafnan/iches-unisya/issues/9) are now **GO CODING — Codex VS Code ONLY**. Branch `feat/ich-s0-04-scoped-workspaces` from latest clean main; narrowly scoped server-authoritative role/event/institution permissions, multi-role same-user workspace switching and minimal Vue UI. **No new migration/seeder execution, DB reset, push, PR, merge, deployment or separate CMS/frontend redesign authorized.** Transactional opt-in MySQL tests against the owned local schema permitted after exact DB verification; no production secrets. Codex must stop after a local commit with concise evidence; ChatGPT independently reviews. **No S0-04 implementation or test result claimed yet.** Frontend palette/fonts locked; COMING SOON flyer is content inventory only. Earlier S0-04 NO_GO snapshots below are superseded.

> **LATEST — 28 OCTOBER 2026 / ICH-S0-03 MERGED / COMPLETE, PR #8 CLOSED:** Owner explicitly authorized **GO MERGE PR #8** only after checks. GitHub verified **exact 33 files**, head `6b32e7580fb04fb789908a5f486d11d1bfa24adb`, CI [run 40709950520](https://github.com/akhmadafnan/iches-unisya/actions/runs/40709950520) SUCCESS on PHP 8.4/Node 22, PR mergeability true, no required status checks or active rulesets. Normal **merge** with exact expected head guard succeeded, merge commit **`3df38cf4229e3aec011a2d20fee54984d6560ccc`**. Postmerge GitHub PR `CLOSED/merged:true`, main pointed to that SHA, parents are `2331e3857fd5330f5eaaa3c6652062fa7bf53bb4` and exact authorized feature head. [21K-11 postmerge report](21K-11-S0-03-MERGED-COMPLETE.md). No reset, extra migration, app code changes, deployment or PR #3 merge. **ICH-S0-04** [21L-01](21L-01-S0-04-SCOPED-RBAC-WORK-ORDER.md)/[Issue #9](https://github.com/akhmadafnan/iches-unisya/issues/9) **PREPARED / NO GO CODING**. Frontend **Inter + Sora, ivory/forest/near-black/gold** remains locked; flyer information only. All prior open/CI-failed entries below are historical.

> **LATEST — 28 OCTOBER 2026 / OWNER FRONTEND DESIGN RECONFIRMED; S0-04 PREPARED:** Flyer COMING SOON is **INFORMATION ONLY**, NOT a website palette, font, layout or graphic-style reference. Locked frontend remains **Inter + Sora; ivory #F7F6F1, forest #164734, near-black #17211C, gold #B79A5B; academic-editorial, no carousel, EN/ID/AR RTL**; logo originals keep source colors. [Brand README](brand/README.md) updated with explicit override. [21L-01 S0-04 Scoped RBAC + Switch Workspace Work Order](21L-01-S0-04-SCOPED-RBAC-WORK-ORDER.md) and [Issue #9](https://github.com/akhmadafnan/iches-unisya/issues/9) prepared with **NO GO CODING**; user switching stays within one authenticated user, staff support impersonation deferred. **PR #8** remains OPEN / NOT MERGED at latest audit; head `6b32e7580fb04fb789908a5f486d11d1bfa24adb`, PHP8.4/Node22 workflow [40709950520](https://github.com/akhmadafnan/iches-unisya/actions/runs/40709950520) SUCCESS, observed required-status-checks enforcement off and no active rulesets; protection endpoint gave 403 but branch summary `protected=false`. **Next gate: separately authorized GO MERGE PR #8**, then separately GO CODING S0-04. No application code, DB, deployment or PR merge performed in this planning update. Old flyer-as-visual-reference language is superseded.

> **LATEST — 28 OCTOBER 2026 / ICH-S0-03 REV-01 PUSHED, CI GREEN, PR #8 OPEN / NOT MERGED:** Owner explicitly approved **GO PUSH REV-01**. After local read-only review of `6b32e7580fb04fb789908a5f486d11d1bfa24adb` (parent `6f7f9beb...`, test-only unique IP correction, 3 files, clean tree), normal non-force push updated [PR #8](https://github.com/akhmadafnan/iches-unisya/pull/8) to exact REV-01 SHA. [Actions run #40709950520](https://github.com/akhmadafnan/iches-unisya/actions/runs/40709950520) **SUCCESS**, including DB-free PHP 8.4 tests, Pint, Node 22 dependency install, TypeScript and Vite build. PR is OPEN, 2 commits, 33 paths, mergeability true at last check. **No merge authorization, deployment, reset or additional migration.** User also explicitly authorized saving original logos to repo: 5 original transparent WebP files plus [docs/brand/README.md](brand/README.md) committed to main at `efff11b86bed700a2bb2742cd4925f7939a0e669`; ZIP SHA checked exact against uploaded source. COMING SOON flyer provided as content/visual reference; speaker/date facts not yet treated as confirmed CMS data. Earlier CI_FAILED entries below are **historical and superseded**.

> **LATEST — 28 OCT 2026 / ICH-S0-03 PR #8 OPEN, CI_FAILED_REV01_REQUIRED:** Owner's “Oke siap Lanjutkan” authorized reviewed normal push and PR, **not merge/deploy**. Exact `6f7f9beb...` pushed successfully to `feat/ich-s0-03-passwordless-auth` and PR [#8](https://github.com/akhmadafnan/iches-unisya/pull/8) created with 32 files, mergeable. [GitHub Actions run 40706833873](https://github.com/akhmadafnan/iches-unisya/actions/runs/40706833873) **FAIL** at DB-free `AuthenticationSmokeTest.php:80` (9 PASS / 1 FAIL); Linux file-cache limiter bleeds across test methods; Pint and Node22 steps SKIPPED. Failure independently reproduced locally with CI-like `CACHE_STORE=file` and DB disabled. [21K-09 REV01 diagnosis and bounded Codex correction](21K-09-S0-03-PR8-CI-REV01.md). **No merge, source edit, additional migration, reset, deployment or S0-04 authorized.** Earlier GO/preparation statements below are historical as applicable.

> **LATEST OWNER AUTHORIZATION — 28 OCTOBER 2026: ICH-S0-03 GO CODING / AUTHORIZED FOR CODEX VS CODE ONLY.** Owner explicitly approved feature-branch coding, new additive non-destructive migrations and testing per [21K-08](21K-08-S0-03-CODEX-WORK-ORDER.md) and [21K-07 functional spec](21K-07-S0-03-AUTHENTICATION-MVP-SPEC.md). Codex should produce code and LOCAL commits, then hand off to ChatGPT for diff review. **No actual S0-03 code/tests are claimed yet. No reset/DB wipe, push, PR creation, merge or deployment authorized.** Older NO_GO references below are historical status snapshots, now superseded. Source base latest at GO should be verified locally by Codex after safe fetch. Keep owner demo policy `@iches.test` / local-only hashed `password`; do not change production passwordless auth.

> **CURRENT LOCAL PATH (owner-directed relocation, 28 Oct 2026):** `D:\PINJAM-AFNAN\Herd\iches-unisya`. The repository was moved intact from `D:\PINJAM-AFNAN\iches-unisya`; the old directory no longer exists. Herd Free `herd links` confirms `http://iches-unisya.test/` points to the **new** path using PHP 8.4. Git remote `akhmadafnan/iches-unisya`, branch `docs/19e-agent-workspace`, HEAD `1403178c38c131bd81ab8ee48a204b5bdec2d494`, clean after move. VS Code was opened again targeting the new directory. Older references to the old path in snapshot sections are **historical** and must not be used as the current workspace. No database, source code, migrations or other Herd links were altered. **NO GO CODING.**

> **UPDATE 28 OCT 2026 — SETUP AUTHORIZED & COMPLETED; NO GO CODING.** On connected Windows desktop the owner-authorized canonical GitHub clone now exists at `D:\PINJAM-AFNAN\iches-unisya` (hyphen), remote `akhmadafnan/iches-unisya`, clean branch `docs/19e-agent-workspace` tracking origin, initial checkout SHA `432dcd012fa49b25019ad6464e7dc3759b99cd11`. The chosen VS Code at `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (space), v1.143.1, **opened the ICHES workspace**. OpenAI Codex extension `openai.chatgpt@26.51021.22027` is installed in the shared Windows user profile; **in-app sign-in/Skills loading NOT VERIFIED**. Four repo Skills plus AGENTS/PLANS verified. Git clone and GUI launch were done with user setup approval, **not application coding GO**. PR #1 is still draft/unmerged, and disposable MySQL DB plus technical decisions are pending. Earlier lines describing the target folder as absent or GUI as unlaunched are pre-setup historical observations.

> **Snapshot 28 Oktober 2026 · DRAFT · BELUM GO CODING.** Ini **satu sumber status operasional jangka pendek**, bukan pengganti [19B backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md), [19C Work Orders](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md), atau [09 Register keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md). Update oleh **ChatGPT** setelah verifikasi bukti GitHub/operator.

## 20A Master Plan and Harness (DRAFT)

[PLANS.md](../PLANS.md) now contains the single M0–M9 roadmap; [20A contract](20A-MASTER-PLAN-SKILLS-AND-HARNESS.md) proposes the Skill registry and evidence workflow. The live status remains ONLY this 19F board. 20B sprint workflow and append-only evidence exception are approved as policy, but **no named GO Coding** has been issued. Per-task GO remains fallback. No application tests have run.

## Tahap 20B — Governance approved, NO GO CODING

Owner menyetujui **GO sprint terbatas**, pengecualian Codex **append-only build log**, serta dua Skills baru: Backoffice shadcn-vue dan QA. Berkas disiapkan pada Draft PR #1; **belum ada GO untuk Task ID/sprint nyata**. Pending: review/merge PR, keputusan teknis, inspeksi VS Code/toolchain, Sprint Work Order READY, GO terpisah.

## 20C — Read-only local preflight (28 Oct 2026)

- Connected Windows device verified: **Git 2.55.0, PHP 8.4.24, Composer 2.10.1, Node 26.4.0, npm.cmd 11.17.0**, PHP pdo_mysql available. No install or application test executed.
- **PARTIAL**: VS Code utama v1.143.1 terdeteksi lewat metadata; Codex extension/sign-in **pada instalasi utama** belum diverifikasi, clone ICHES belum ada, MySQL server/test DB belum diuji. `npm.cmd` berjalan.
- [20C device audit](20C-LOCAL-PREFLIGHT-AND-GATES.md) and [Sprint-01 DRAFT](20C-01-DRAFT-SPRINT-01-WORK-ORDER.md): `ICH-S0-01` → `ICH-S0-02` → `ICH-S0-07`; status **BLOCKED_DECISION / NO_GO**.
- Next action: verify Codex sign-in on owner's v1.143.1 installation, then obtain separate setup permission for project folder/clone and close PRE-02 decisions. No coding.

## Owner local workspace decision — 28 Oct 2026

**Lokasi proyek disepakati:** `D:\PINJAM-AFNAN\iches-unisya` (**pakai tanda hubung**); folder belum ada. **VS Code utama yang dipilih owner:** `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (**pakai spasi**), v**1.143.1** terverifikasi dari metadata. Ada instalasi lain v1.128.0 pada direktori bertanda hubung, tetapi **bukan default yang dipilih**. Ekstensi/login Codex pada v1.143.1 belum diverifikasi; `new_code.cmd` wrapper gagal karena merujuk `Code.exe` yang tidak ada. Tidak ada clone/create/merge/coding. [Detail 20C](20C-LOCAL-PREFLIGHT-AND-GATES.md). **Next:** izin setup clone terpisah lalu inspeksi remote dan kesiapan environment.

## 20C — Codex VS Code UI confirmed by owner screenshot (28 Oct 2026)

Screenshot shows ICHES folder open, `docs/19e-agent-workspace` active on VS Code status bar, Codex sidebar and prior chats visible, plus `AGENTS.md` / `PLANS.md` / `.agents`. This confirms **visible Codex UI session**, but **does NOT prove all 4 Skills can be read by the agent**. Next action: run the no-code [20C-02 Read-only Codex Handshake](20C-02-CODEX-READ-ONLY-HANDSHAKE.md) in Codex chat and review its textual output. No code/DB/install/merge/GO. PRE-02 note: NEW-003 approved participant Magic Link/OTP vs draft RD-06 password login must be reconciled before authorizing code.

## PRE-03 Codex handshake — VERIFIED (28 October 2026)

**Evidence:** owner copied the Codex VS Code agent's complete read-only response, not just a screenshot.

- **Workspace:** `D:\PINJAM-AFNAN\iches-unisya`; canonical remote `https://github.com/akhmadafnan/iches-unisya.git`.
- **Branch:** `docs/19e-agent-workspace`; handshake HEAD `c9df6d0b30d6f3fdb88d94e086555f1a5af365f8`; worktree clean, local tracking ahead/behind `0/0` (agent did not fetch).
- **Read in full:** `AGENTS.md`, `PLANS.md`, `docs/19F-CURRENT-SPRINT-BOARD.md`, `WORKSPACE.md`, `docs/19E-AGENT-WORKSPACE-RUNBOOK.md`.
- **All 4 Skills directly read by Codex:** `iches-codex-engineering`, `iches-public-frontend`, `iches-backoffice-ui`, `iches-code-verification`. Direct readability **PASS**; **automatic Skill triggering not tested**.
- **Agent behavior:** no source/docs/file changes, no install, test, commit, fetch, migration or named GO. Thus **PRE-03 Codex access/readiness PASSED**; full **G0 runtime readiness still PARTIAL** due to DB test account and owner decision gates.
- **Later SHA may differ** because ChatGPT maintains the documentation branch; snapshot SHA is intentional and historical.

**Separate non-writing database connectivity evidence:** Windows service `MySQL97` reports **Running**; executable metadata product version **9.7.1.0**; TCP connect to `127.0.0.1:3306` **succeeded**. This does **not** verify MySQL credentials, server SQL response, storage engine, migrations, or an isolated test DB. No credentials read or stored.

**Next active gate:** [20D PRE-02 Technical Decision Packet](20D-PRE-02-TECHNICAL-DECISION-PACKET.md) owner approval, then isolated test DB and final Sprint 01 readiness. **NO GO CODING.**


## 20D — RD-01–RD-06 FINAL (28 October 2026)

**Owner explicitly approved:** “Setuju paket 20D”. Technical decision register [09](09-KEPUTUSAN-DAN-ISU-TERBUKA.md) updated for **RD-01..06**; participant **OTP/Magic Link without password** retained. [20D packet](20D-PRE-02-TECHNICAL-DECISION-PACKET.md) now marked approved. **ICH-PRE-02 decision bundle COMPLETE**; no need to ask those six choices again.

**Database preflight, nonwriting:** Windows `MySQL97` Running; TCP `127.0.0.1:3306` handshake protocol `10`, server version **9.7.1**, **no login/SQL/schema action**. Still **BLOCKED_G0**: authenticate isolated *test* database and verify InnoDB/FKs/migration permissions before any database task. Governance PR #1 still Draft and unmerged; Sprint Work Order still **NO_GO**. **Next one action:** verify safe isolated test DB environment, then review/merge documentation PR through separate approval.

## 20E — Database environment preflight (read-only)

**Actual Windows evidence 28 October 2026:**
- MySQL97 service Running; native protocol handshake version **9.7.1** and TCP localhost:3306 reachable.
- Found **native MySQL 9.7.1 command-line client** at `C:\Program Files\MySQL\MySQL Server 9.7\bin\mysql.exe`. It isn't on PATH; absolute executable works. No reinstall necessary.
- **No DB login/password, SQL, schema or data changes** were attempted. **G0-DB-01 PASS**; G0-DB-02 (test-only login) and G0-DB-03 (InnoDB/FK/scoped grants) **BLOCKED**. G0-DB-04 migration/rollback is NOT_RUN and needs named coding GO.
- [20E Test DB Readiness Runbook](20E-MYSQL-ISOLATED-TEST-DB-READINESS.md). No test schema/user assumed to exist and no provisioning authorization received. **No GO coding.**

## 20E administrator-auth checkpoint — blocked safely (28 October 2026)

Owner explicitly approved provisioning **only** a new isolated `iches_unisya_test` database and limited `iches_test` account. Attempts to confirm existing administrative access **failed safely**: a **single no-password** root read-only connection returned MySQL **1045 Access denied**; a **single default-profile** read-only connection also returned **1045**. There was **no password guessing, no inspection of existing saved passwords, no SQL DDL, no user/database created**, and no production data touched. MySQL 9.7.1 server and client remain operational.

**Next action:** the owner must type administrator password locally once into a **temporary MySQL login path**, without posting it into ChatGPT or Codex. Full instructions: [20E-01 Privileged Login Setup](20E-01-TEMPORARY-MYSQL-ADMIN-LOGIN.md). After owner reports setup complete, ChatGPT can test admin account and provision only authorized test DB/user. Remove temporary administrator login path once done. **No GO Coding or PR merge.**

## 20E-02 — Owner Herd Free/phpMyAdmin local environment CONFIRMED

- Owner clarified database management via phpMyAdmin in `D:\PINJAM-AFNAN\Herd\phpMyAdmin`; `https://phpmyadmin.test/index.php` HTTP **200** on 127.0.0.1; authenticated session not verified.
- **Herd Free 1.29.0** used: `herd link iches-unisya` SUCCESS; `herd links` lists `http://iches-unisya.test` mapped to the exact GitHub checkout folder, **PHP 8.4**. Existing other link unchanged.
- `http://iches-unisya.test/` HTTP **404** because repo is **docs-only** (`public/` and `artisan` absent); site registration is verified, not app readiness.
- [20E-02 GUI workflow](20E-02-HERD-FREE-PHPMYADMIN-WORKFLOW.md) supersedes temporary CLI login prompt as default. Owner still needs to create **only** `iches_unisya_test` and `iches_test` via normal authenticated phpMyAdmin GUI (unless collision); **not created yet**.
- G0-DB-02/03 **BLOCKED**; PR #1 still Draft, **NO_GO Coding**.


## 20F — Owner-directed ICHES folder relocation COMPLETED

**CURRENT WORKSPACE:** `D:\PINJAM-AFNAN\Herd\iches-unisya` (moved intact from `D:\PINJAM-AFNAN\iches-unisya`). Verified old folder absent, new folder present, `git status` clean, expected Git HEAD, and `herd links` resolving `http://iches-unisya.test/` to new folder using PHP 8.4. Existing `sip-hibah-v2` Herd link remained intact. Chosen VS Code `new_Code.exe` launched again with the **new** folder. **Database not touched**; user's existing Herd Free + phpMyAdmin workflow is accepted as the operating pattern. App remains documentation-only so HTTP 404 is expected until separately authorized Laravel implementation. **No GO Coding**. Historical path references above are not active instructions.

## 21A/21B — PR audited and first foundation task prepared

- [21A review](21A-PR1-GOVERNANCE-REVIEW.md): 24 docs/Skills changed at audit snapshot, GitHub PR mergeable, 263 relative documentation links checked, 0 broken, 0 application source changes; **owner merge approval pending**.
- [21B Work Order](21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md): **ONLY `ICH-S0-01`**, Laravel 13/Inertia/Vue3/TS/Tailwind4 **DB-INDEPENDENT** boot at `D:\PINJAM-AFNAN\Herd\iches-unisya` (Herd Free). No migrations, user schema, password auth or CMS. Pending merged docs baseline and named `GO CODING ICH-S0-01`.
- [21C acceptance](21C-S0-01-ACCEPTANCE-AND-HANDOFF.md): actual Composer/PHP/npm build/typecheck/GET/404 checks and HTTP **200** at Herd URL must be verified after implementation. **Not run.**
- Runtime minimum Laravel 13 PHP extensions checked present. MySQL test-DB readiness remains open for S0-02, **not needed for S0-01**.
- **Next one action:** owner reviews and authorizes governance PR merge. **No GO Coding, no merge, no DB writes.**


## 21A — PR #1 MERGED; ICH-S0-01 ready for owner GO (28 Oct 2026)

- **Owner explicitly authorized** `MERGE PR #1`, no application coding GO.
- GitHub recheck: 27 Markdown/Skills changes, zero app source, clean mergeability. Merge completed successfully to `main` at **`275cfbfd0e431033eb05d2c519eda19282465962`**. [Merged PR](https://github.com/akhmadafnan/iches-unisya/pull/1).
- Local repo `D:\PINJAM-AFNAN\Herd\iches-unisya` switched from docs branch to **`main`**, fast-forward synchronized to merge SHA and Git working tree clean; docs branch retained.
- [21B Task Work Order](21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md) is **READY_FOR_OWNER_GO** for **DB-INDEPENDENT ICH-S0-01 only**, with approved governance merge baseline recorded. **No GO Coding has been issued**, feature branch not created, no app install/build/DB work occurred.
- **Next action:** owner sends `GO CODING ICH-S0-01` if ready. Codex checks latest clean `origin/main` SHA, approved merge ancestry and exact Work Order before any code modifications. S0-02 test DB gate remains separate.

## 21D — Owner GO CODING ICH-S0-01 received (28 October 2026)

**Verbatim explicit owner instruction:** “GO CODING ICH-S0-01. Saya memberikan izin eksplisit untuk Task ICH-S0-01 sesuai Work Order 21B yang sudah READY. Implementasi kode hanya oleh Codex VS Code pada feature branch; tanpa migrasi database, autentikasi, CMS, auto-merge, atau deployment. Siapkan instruksi eksekusi final Codex.”

**State:** ICH-S0-01 is now **GO_AUTHORIZED / PENDING_CODEX_EXECUTION**. Only Codex VS Code may modify source under [21B Work Order](21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md) and [21D execution prompt](21D-ICH-S0-01-GO-CODEX-EXECUTION.md). ChatGPT has **not** written Laravel source or created the feature branch. Last verified clean local `main` before this documentation update: `3371ac1532a7856a2bd7d248f79d563a541dac4a`, on `D:\PINJAM-AFNAN\Herd\iches-unisya`; actual coding branch-point SHA must be rechecked after GO documentation sync.

**Expected handoff:** Codex creates `feat/ich-s0-01-foundation`, builds DB-free Laravel 13/Inertia/Vue 3/TS/Tailwind4 with actual tests, returns changes/exit codes/Herd HTTP, no auto merge or deploy. **ICH-S0-02, ICH-S0-07 and full ICH-SPRINT-01 remain NOT_AUTHORIZED.** Test DB G0 gate still open, irrelevant to this DB-free first task.


## 21E — Independent code audit `ICH-S0-01` complete (28 October 2026)

- **ChatGPT reviewer decision: `REVIEW_PASS / OWNER_ACCEPTANCE_PENDING`**; see [21E review report](21E-ICH-S0-01-INDEPENDENT-CODE-REVIEW.md).
- Inspected Codex's **local, unpushed commit `8eba7cb5b9da0577910beb919844165869591e05`** on `feat/ich-s0-01-foundation`; baseline `4137dce48e85fab5c25c89b7f3a1fde1d0fa3b89`. Git clean; **55 changed files** all within code/test/lockfile/allowed harness boundaries; no governance/docs/Skills edits, migrations, authentication or tracked `.env`.
- Re-ran `composer validate --strict`, `php artisan --version`, filtered and full PHP smoke suite (3 PASS, 22 assertions), Pint, npm TypeScript check/build, Composer platform, Herd root HTTP 200/unknown 404, HTTP requests to private/config paths (404) and Git scope checks. All listed checks **PASS**. Inspected actual desktop/mobile screenshots.
- **Minor, not blocking:** `public/favicon.ico` empty (0 bytes), replace in public visual task; database config intentionally disabled until separately authorized S0-02.
- **No push/PR/merge/deploy** has occurred. **Next:** owner accepts S0-01 and permits **Codex** to push the feature branch and open a PR (not auto merge). S0-02/07 and full Sprint still lack GO.

## 21F — ICH-S0-01 OWNER_ACCEPTED / CODEX_PUSH_PR_AUTHORIZED (28 October 2026)

- **Owner explicit acceptance:** “SETUJU ACCEPT ICH-S0-01. Saya menerima hasil review 21E dan mengizinkan Codex VS Code melakukan push branch feat/ich-s0-01-foundation serta membuat Pull Request ke main. Tidak ada izin auto-merge, migrasi database, atau deployment. Siapkan instruksi Codex berikutnya.”
- [21E independent review](21E-ICH-S0-01-INDEPENDENT-CODE-REVIEW.md): **REVIEW_PASS** on local clean feature HEAD `8eba7cb5b9da0577910beb919844165869591e05`. Owner now **ACCEPTED** that exact implementation.
- [21F Codex push/PR handoff](21F-ICH-S0-01-OWNER-ACCEPT-PUSH-PR-HANDOFF.md): Codex must preserve existing feature branch and commit, verify `git diff origin/main...HEAD`, push **only** `feat/ich-s0-01-foundation` without force, open feature PR into `main` and stop. Windows `gh` CLI was not found; use authenticated GitHub UI or return compare URL for ChatGPT connector fallback.
- **No push, feature PR or merge has been verified yet.** Current GitHub `main` remains docs/governance only; local Laravel implementation lives only on feature branch pending Codex push.
- **No next-task GO:** S0-02, S0-07, schema/migration, auth, CMS and deployment remain unauthorized.

## 21G — Skills Governance & Integration (parallel docs-only PR; 28 October 2026)

- **Owner agreed** to curated external Skills (eight source repositories), preserving native ICHES Skills as highest priority. This is **documentation/Skills governance work**, not GO CODING and not authorization to install global tools.
- On isolated remote branch `docs/21g-skills-integration` from `main` `667b1180578d7e741b6b1298b8d28e8eefcaba9c`: W1 pilot **five pinned, exact third-party Skills** (Ponytail general/review/audit; Matt diagnosing-bugs and TDD), bundled needed references, original MIT notices, and explicit skill routing/activation gates. **No app PHP/Vue/TS changes.**
- [21G-01 routing](21G-01-SKILLS-ROUTING-POLICY.md), [21G-02 provenance](21G-02-SKILLS-PROVENANCE-AND-LICENSES.md), [21G-03 tests](21G-03-ACTIVATION-AND-WAVE-PLAN.md). W2 Taste/Anti-Slop/Vercel and W3 iart/Anthropic/Caveman **not installed** yet, pending per-pack audit.
- **Codex activation G21G-04..07 NOT RUN**, until PR owner merge + safe sync. PR must remain **DRAFT / UNMERGED** pending review and explicit owner merge approval. This work **must not switch or reset the local unpushed** `feat/ich-s0-01-foundation` branch.
- **Operational blocker remains:** accepted `ICH-S0-01` feature push/PR failed due GitHub terminal credential helper, no remote feature branch/PR yet. No change to that status and no deployment.

## 21G W1 audit evidence (28 October 2026)

- **G21G-01/02/03 PASS:** 5 upstream Skills, 3 supporting files and 2 MIT license texts **all exact-copy** from pinned commits; 81 relative Markdown links checked with 0 broken; 17 changed paths all confined to docs/Skills/licenses, no app code.
- Local `feat/ich-s0-01-foundation` HEAD `8eba7cb5b9da0577910beb919844165869591e05` independently verified untouched and clean. **No local checkout, push or PR for foundation performed.**
- **G21G-04..08 NOT_RUN / PENDING:** actual Codex discovery/activation, negative safety tests and owner-approved merge. [Detailed W1 audit](21G-04-W1-GITHUB-QUALITY-AUDIT.md).
- Draft PR to be created from `docs/21g-skills-integration` (not yet merged); no new application coding GO issued.

## 21G — PR #2 merged and isolated read-only Skills QA prepared (28 October 2026)

- **Owner explicitly approved `MERGE PR #2`** and read-only Codex testing. [PR #2](https://github.com/akhmadafnan/iches-unisya/pull/2) **MERGED** at `e5362f919fbd7ce23fdd50761b5dc99fa9410e18`. Final GitHub check: 18 Markdown/agent assets only, no app code and no conflicts.
- Windows `origin/main` fetched. Five third-party Skills are readable and frontmatter checks **5/5 PASS** on isolated detached QA worktree `D:\PINJAM-AFNAN\ICHES-SKILLS-READONLY`, HEAD PR #2 merge SHA; nine total Skills including four native ICHES; QA Git clean.
- Opened separate VS Code window for the isolated worktree and [21G-03](21G-03-ACTIVATION-AND-WAVE-PLAN.md). **Real Codex invocation/auto-selection has NOT occurred**, so G21G-05/06/07 remain **NOT_TESTED**. Use [21G-05 Codex read-only handoff](21G-05-POST-MERGE-CODEX-READONLY-TEST.md).
- Main Laravel worktree `feat/ich-s0-01-foundation` remains at `8eba7cb5b9da0577910beb919844165869591e05`, clean and unchanged. The accepted feature branch still cannot be pushed because Windows Git HTTPS credentials are unconfigured.
- **Next:** user runs the copy/paste read-only prompt in the separate Codex VS Code window and returns actual results. **No coding GO, application merge, DB migration or deployment**.


## 21G-06 — W1 read-only Codex validation reviewed (28 October 2026)

- **W1 accepted for task-bounded reference use** after owner-provided Codex report: 5 Skills explicitly readable PASS; 5 hypothetical routing scenarios PASS; 3 negative permission scenarios PASS; native ICHES precedence PASS. All routing/permission results are **simulations**, not proof of automatic invocation.
- **AUTO-ACTIVATION = NOT_TESTED**. Do not claim Skills auto-trigger based on manual `Get-Content` or scenario answers. [Full review & independently verified Git state](21G-06-CODEX-READONLY-VALIDATION-REVIEW.md).
- Independent Windows read-only recheck: detached QA HEAD `80b888e55a98dad241122518918168db1b5020da`, `git diff --exit-code` and `git diff --cached --exit-code` 0, 5 YAML frontmatters match and 4 native Skills present; accepted Laravel branch `feat/ich-s0-01-foundation` still at `8eba7cb5b9da0577910beb919844165869591e05`, clean.
- **Next parallel documentation task:** `21H` selective, read-only evaluation of Taste Skill + Anti-Slop Design + Vercel UI Guidelines; no install/coding without separate scope. Operational app blocker remains GitHub local HTTPS authentication for accepted ICH-S0-01 push/PR.


## 21I — Foundation PR #4 merged / next 48h gates (28 October 2026)

- **Owner authorized and GitHub confirmed MERGE PR #4** with exact feature head `8eba7cb5b9da0577910beb919844165869591e05`; merge SHA `561baf322d459d898f5f96d280f5a5f02634c59f`. Exactly 55 reviewed changed files, no conflict, **no CI runs configured**. This overrides older historical `PENDING` status.
- [21I-01 post-merge critical path](21I-01-ICH-S0-01-MERGE-AND-48H-NEXT-PATH.md) and [21I-02 CI Work Order](21I-02-ICH-S0-07-CI-WORK-ORDER.md). **`ICH-S0-07` is READY_FOR_OWNER_GO, NOT_AUTHORIZED**; DB test gate `ICH-S0-02` remains blocked/unverified.
- [PR #3](https://github.com/akhmadafnan/iches-unisya/pull/3) design Skills stays open/unmerged, separate approval. No deployment, schema/auth/CMS code task or additional PR merge authorized.

## 21I-03 — Owner authorized bounded Codex CI task (28 October 2026)

- **Exact owner message:** “Wokkey sepakat. Silahkan execute `ICH-S0-07` sesuai dengan arah anda”. This is explicit **GO CODING ICH-S0-07** under [21I-02](21I-02-ICH-S0-07-CI-WORK-ORDER.md).
- [21I-03 Codex VS Code execution handoff](21I-03-GO-ICH-S0-07-CODEX-EXECUTION.md) is ready, with safe local `main` fast-forward from historical feature branch and scope-limited GitHub Actions implementation. ChatGPT did **not** code/push the CI workflow.
- **State:** GO_AUTHORIZED / CODEX_EXECUTION_PENDING. Owner's feature branch `feat/ich-s0-01-foundation` at accepted `8eba7cb5` was read-only verified clean. Local `main` was stale, so Codex must safely sync before creating `feat/ich-s0-07-ci`.
- **Hard stops:** S0-02 test DB/unapproved schema, passwordless auth, RBAC, frontend/CMS, finance, app PR merge, deploy all outside this authorization; PR #3 design Skills remains open and separately gated.
- **Publication:** coding GO alone does not authorize push/PR/merge. Codex returns local evidence for independent review; owner then decides.

## 21I-04 — Independent review of local ICH-S0-07 (28 October 2026)

- Local Codex branch `feat/ich-s0-07-ci`, commit `17e335049f209110e76a03dc99bc588cc7cee925`, base `42a8633`, clean. **Exactly 2 changed files:** CI workflow (73 new lines), build-log append-only (+33, -0). No schema/secret/other code changes. Local Composer/PHP/Pint/npm/TS/Vite/actionlint checks **REPORTED PASS**, GitHub Actions/Node22 `NOT_RUN`.
- [21I-04 independent audit](21I-04-ICH-S0-07-INDEPENDENT-CODE-REVIEW.md): **REVISIONS_REQUIRED (one HIGH)**: `shivammathur/setup-php` pinned to annotated `v2` tag-object SHA `d52fc...` instead of actual commit SHA `eb7c497e18156a6bbabfef1d3a82760b9eda3962`. Direct GitHub Git API confirmed different object types. Other two Action pins valid.
- Existing named `ICH-S0-07` GO covers this narrow code review correction; **Codex only**, append-only correction evidence and new local commit. **No push/PR/merge/deployment permission** yet; owner acceptance pending, Linux CI not run.
- Local accepted `ICH-S0-01` source is preserved. `21H` design Skills PR #3 remains independently open/unmerged.

## 21I-05 — ICH-S0-07 REV-01 closed / independent review PASS (28 October 2026)

- [21I-05 final review](21I-05-ICH-S0-07-FINAL-REVIEW.md) **REVIEW_PASS**: local branch `feat/ich-s0-07-ci`, original CI commit `17e335049f209110e76a03dc99bc588cc7cee925`, follow-up fix `c420d95175d7d25cd9fee6719180acaf0b684903`. Exact ancestry and Git clean independently verified.
- REV-01 `shivammathur/setup-php` commit pin is now `eb7c497e18156a6bbabfef1d3a82760b9eda3962`, dereferenced from official annotated `v2` tag; workflow diff only one line. Append-only log added 21 lines, no deletions.
- Local PHP/Pint/Vue/Vite/actionlint checks recorded PASS by Codex; **Linux/Node22 GitHub CI remains NOT_RUN / PENDING_PUBLICATION**. No feature push/PR/merge yet. Next permission: owner ACCEPT S0-07 and authorize Codex feature push + ChatGPT PR creation, **NO MERGE**.
- All additional product code/DB/production work remains separately authorized. Design Skills PR #3 remains open.

## 21I-06 — Owner accepted CI and authorized Codex publication (28 October 2026)

- Owner explicitly **ACCEPTED ICH-S0-07** after [21I-05](21I-05-ICH-S0-07-FINAL-REVIEW.md), and authorized **Codex VS Code push** of exact local `feat/ich-s0-07-ci` HEAD `c420d95175d7d25cd9fee6719180acaf0b684903`. After verified remote push, ChatGPT may create PR to main and inspect Actions.
- [21I-06 publication handoff](21I-06-S0-07-OWNER-ACCEPT-PUSH-PR-HANDOFF.md) contains verified local preflight and bounded Codex instructions. Latest check: local SHA correct, clean, GCM helper active; remote feature ref **not yet present**.
- **No** merge, migration, DB, next coding task or deployment authorization. Ubuntu 24.04 PHP 8.4/Node 22 CI remains **NOT_RUN** until actual Actions.

## 21I-07 — PR #5 created, actual GitHub CI failed (28 October 2026)

- **PR #5 OPEN / UNMERGED**, head `c420d95175d7d25cd9fee6719180acaf0b684903`; exact two-file scope PASS. [Full live CI failure analysis](21I-07-S0-07-LINUX-CI-FAILURE-REV02.md).
- **Both real GitHub Actions runs FAILED:** [push 40668248799](https://github.com/akhmadafnan/iches-unisya/actions/runs/40668248799), [PR 40669157978](https://github.com/akhmadafnan/iches-unisya/actions/runs/40669157978). PHP setup/Composer PASS; `php artisan test` failed due to `resources/js/Pages` default Inertia testing path vs actual lowercase `resources/js/pages` on Linux; 1 test failure, 2 warnings. Pint/Node22/npm/build skipped.
- **Status REV-02 / REVISIONS_REQUIRED.** Same S0-07 work scope allows bounded Codex-only CI testing fix, preserving assertions and prior commits, but **new corrected SHA must not be pushed without separate owner approval**. PR merge, DB, new coding task and deployment remain NO_GO.

## 21I-08 — REV-02 corrected and independently reviewed (28 October 2026)

- [21I-08 full local review](21I-08-S0-07-REV02-FINAL-LOCAL-REVIEW.md): **REVIEW_PASS / REV-02 CLOSED locally**. Branch `feat/ich-s0-07-ci`, new local HEAD `294a54b223676df3b6e9124480e271ee3a50bdc7`, parent `c420d951`, clean; 3 scoped files, all additive; `.env` ignored.
- Independently reran **`php artisan test --display-warnings` (4 PASS / 36 assertions / no warnings)**, Pint --test (18 files PASS), Composer strict validation (PASS), patch check PASS. Negative missing Inertia component test remains meaningful; case-sensitivity and temporary example env repair reviewed.
- **PR #5 remains OPEN/UNMERGED at old remote `c420d951` and both previous GitHub Actions runs FAILED.** Ubuntu/PHP8.4/Node22 after fix **NOT_RUN**. Owner must separately approve pushing new SHA; Codex-only normal push, ChatGPT checks live CI after, merge independently owner-gated.
- No unrelated new code task, migration, database or deployment authorized. [21H PR #3](https://github.com/akhmadafnan/iches-unisya/pull/3) remains separate.

## 21I-09 — Owner authorized REV-02 corrected commit push (28 October 2026)

- Owner explicitly **ACCEPTED REV-02** after [21I-08](21I-08-S0-07-REV02-FINAL-LOCAL-REVIEW.md) and authorized **Codex VS Code** to push the existing `feat/ich-s0-07-ci` branch at exact local HEAD `294a54b223676df3b6e9124480e271ee3a50bdc7`, normal non-force push. After verified remote update, ChatGPT may inspect PR #5 and real Ubuntu 24.04 / PHP 8.4 / Node 22 CI.
- Local read-only preflight: branch `feat/ich-s0-07-ci`, exact HEAD `294a54b`, upstream `origin/feat/ich-s0-07-ci` still `c420d95175d7d25cd9fee6719180acaf0b684903`, worktree clean, canonical origin, Git Credential Manager active. GitHub API independently confirmed remote still at old SHA. **Push not executed during this ChatGPT turn.**
- [21I-09 Codex push handoff](21I-09-S0-07-REV02-PUSH-AUTHORIZATION.md) scopes this push to the approved commit; **no force push, rebase, amend, extra app code, merge, migrations, DB or deployment**.
- PR #5 remains OPEN / UNMERGED / prior checks failed until an actual corrected SHA run completes. Separate Owner authorization required for PR merge.

## 21I-10 — Real GitHub Actions PASS on corrected PR #5 (28 Oct 2026)

- [21I-10 actual workflow verification](21I-10-S0-07-LIVE-CI-PASS-AND-MERGE-GATE.md): GitHub feature ref and PR #5 exact owner-approved SHA `294a54b223676df3b6e9124480e271ee3a50bdc7`. PR remains OPEN/UNMERGED, **3 scoped changed files**, zero deletions, no conflict.
- [PR #5 GitHub Actions 40678863079](https://github.com/akhmadafnan/iches-unisya/actions/runs/40678863079) **SUCCESS**: clean Ubuntu PHP 8.4.27, Composer install/validate, 4 PHPUnit tests/36 assertions, Pint 18 files, Node22 setup/npm ci/TS typecheck/Vite build. **REV-02 CLOSED**, real CI PASS.
- Duplicate [push run 40678855656](https://github.com/akhmadafnan/iches-unisya/actions/runs/40678855656) CANCELLED, while PR run SUCCESS. GitHub `mergeable=true` but `mergeable_state=unstable`; review required check rules before merge. **Owner merge permission not granted**, no auto-merge, DB or deployment.
- After explicit owner authorization, independently re-check head SHA, 3-file diff and checks before PR #5 merge. Design Skills PR #3 remains separately OPEN/UNMERGED.

## 21I-11 — PR #5 merged; S0-07 complete (28 October 2026)

- Owner explicitly authorized merge of [PR #5](https://github.com/akhmadafnan/iches-unisya/pull/5), with exact accepted HEAD `294a54b223676df3b6e9124480e271ee3a50bdc7`. GitHub standard merge returned **merged=true** and commit `f6d5bed4518e40e3b6d9da44a27661147571b478`, verified on `main`; **3 scoped files**.
- [21I-11 post-merge evidence](21I-11-S0-07-MERGED-AND-NEXT-GATE.md). Successful [PR workflow 40678863079](https://github.com/akhmadafnan/iches-unisya/actions/runs/40678863079): Ubuntu PHP 8.4.27, Composer, Laravel 4 tests/36 assertions, Pint 18 files, Node 22/npm ci/TS/Vite all PASS. Duplicate push run cancelled. `main` protected=false, required checks off/empty; no rulesets, no bypass; ordinary merge succeeded.
- **S0-01 + S0-07 are MERGED/COMPLETE.** Next S0-02 requires isolated DB proof and separate named GO, not granted. No new code, migration or deployment. [Design Skills PR #3](https://github.com/akhmadafnan/iches-unisya/pull/3) open/unmerged.

## 21J-01 — Owner DB policy revised for simpler development (28 October 2026)

- **RD-03B OWNER_DECIDED:** [21J-01 one primary development DB decision](21J-01-SINGLE-PRIMARY-DEV-DATABASE-DECISION.md). Use one application-only development MySQL/InnoDB schema named `iches_unisya` for ICHES trial/error; **NO separate persistent `iches_unisya_test` or `iches_test` required**. Earlier 20D RD-03 engine stays approved; the disposable test DB requirement is superseded. Other projects' databases and production data must never be touched.
- **Database is NOT yet verified as present:** no authenticated connection, ownership check, scoped `iches_dev` account provisioning, InnoDB/FK verification or MySQL SQL executed for this decision. Local CI remains DB-disabled. Must verify name collision and rights before Codex schema work.
- **No automatic `migrate:fresh`/`db:wipe`/DROP/TRUNCATE** on the primary development DB. Future trial-data purge before production requires explicit per-operation approval and a restorable backup; preferred clean production database is a separate release decision.
- **`ICH-S0-02` remains NO_GO** until an adapted work order and named GO; this change is governance-only and does not authorize database setup, migrations or deployment. **S0-01 and S0-07 remain MERGED/COMPLETE**, PR #3 remains open.

## 21J-02 / 21J-03 — Fast development DB decision and next Codex task (28 October 2026)

- Owner chose exact database name **`iches-unisya`** (hyphen), to create manually in phpMyAdmin; temporary local root account chosen for simple development. [RD-03C final](21J-02-ICHES-DEV-DB-NAME-AND-LOCAL-ACCESS.md) **supersedes RD-03B** only for name/account suggestion. No separate test schema. No credential values stored publicly.
- [21J-03 fast Work Order](21J-03-ICH-S0-02-FAST-TRACK-WORK-ORDER.md) READY for owner manual DB confirmation; current Laravel `config/database.php` still has empty connections, so later Codex must wire MySQL. `.env` stays ignored. CI remains DB-disabled and passing.
- **NOW:** owner creates `iches-unisya` manually; **`ICH-S0-02` not started**. One named GO after creation can authorize the bounded Codex DB foundation task. Root must be local-only; no automatic destructive database reset or production deployment.
- **S0-01 and S0-07 MERGED/COMPLETE**; [PR #3 Skills](https://github.com/akhmadafnan/iches-unisya/pull/3) OPEN/UNMERGED.

## 21J-04/05 — Owner corrected DB name and granted S0-02 GO (28 Oct 2026)

- Latest owner: `iches_unisya` **UNDERSCORE**, not prior `iches-unisya`; manually created in phpMyAdmin, explicitly `GO CODING ICH-S0-02`. [21J-04 decision](21J-04-ICHES-UNDERSCORE-DB-AND-S0-02-GO.md), [21J-05 Codex execution](21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md).
- Scope: dedicated MySQL/InnoDB local DB, temporary root credentials only in ignored `.env`, minimal identity/events UUIDv7 schema, normal additive migrations after Codex authenticates and confirms database. DB-free CI preserved.
- **Status GO_AUTHORIZED / CODEX_EXECUTION_PENDING / AUTH_CHECK_REQUIRED**. Last known local clean checkout on `feat/ich-s0-07-ci`, main tracking stale; Codex safely syncs main and creates `feat/ich-s0-02-db-foundation`. No reset/wipe, push, merge, deployment or S0-03 auth logic.
- S0-01 + S0-07 COMPLETE, [PR #3](https://github.com/akhmadafnan/iches-unisya/pull/3) remains open/unmerged.

## 21J-06 — MySQL 1045 checkpoint and selective UUIDv7 clarification (28 Oct 2026)

- Codex reports main synchronized to `19ab3e52921a9a51817e8424ec6daadfd00c1e79`, clean feature branch `feat/ich-s0-02-db-foundation` created; MySQL root connection to `127.0.0.1:3306/iches_unisya` **1045 / exit 1**. Per approved 21J-05 Codex stopped **before source edits, migrations or new commit**.
- Owner should enter the correct local DB credential into Git-ignored `.env` and report only `kredensial lokal siap`. No password in chat, public GitHub or logs. No password guessing/reset by Codex. Same existing S0-02 GO authorizes continuation after credential fix.
- **RD-04 clarified:** UUIDv7 for **standalone domain entity primary keys** (`users`, `institutions`, `events`), and compatible UUID foreign keys; **not** all IDs. Pure pivots may use composite primary/unique keys with no `id`. Role/status/reference keys are short codes, ordering and counters integers, Laravel internal tables their required formats. [21J-05 scoped engineering instructions](21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md).
- No DB or code changes by ChatGPT. No push/PR/merge/deploy. Only resume Codex after local credential readiness.

## 21J-07 — MySQL 1045 resolved / Codex S0-02 unblock (28 Oct 2026)

- Owner updated local `.env` credentials; independent PHP PDO probe authenticated **successfully** to `127.0.0.1:3306/iches_unisya`. `SELECT DATABASE()` = `iches_unisya`; MySQL **9.7.1**, default **InnoDB**, foreign-key checks **1**, **0 tables**; **READ_ONLY / EXIT 0**. `.env` remains ignored. No DB DDL/DML or app source edits performed.
- Old MySQL 1045 status is **resolved**; [21J-05 current Codex GO](21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md) updated to `DB_AUTH_VERIFIED / CODEX_RESUME_READY`. Owner's existing GO applies; **no second GO or credential sharing needed**.
- Codex should verify clean `feat/ich-s0-02-db-foundation`, fetch/ff-only latest `main` safely, then implement scoped UUIDv7 identity/event foundation and normal additive migrations. No destructive DB reset, push/PR/merge/deploy. Identity IDs selective per RD-04 (not blanket UUIDs).

## 21K-01 — S0-02 independent audit / REV-03 (28 October 2026)

- [21K-01 full audit](21K-01-S0-02-INDEPENDENT-REVIEW-REV03.md): Codex local `feat/ich-s0-02-db-foundation` HEAD `78bc6d9a904c86b334c28f645d908452295ba26e`, direct parent `3ad72cfb`, clean, **13 scoped file changes**, no secrets in tracked `.env`.
- Independently reran **composer validate (0)**, **PHP smoke 4/36 (0)**, **Pint 28 files (0)**, **npm typecheck (0)**, **Vite build (0)**, and patch check (0). Codex reports opted-in DB tests **7/95 PASS**; reviewer did not rerun tests that temporarily write DB data.
- Independently authenticated read-only `iches_unisya`: MySQL 9.7.1/InnoDB/FK on, `users`/`institutions`/`events`/`role_assignments` all 0 rows, `migrations` 1 row; UUID PK + matching FKs PASS.
- **REV-03 BLOCKING:** real unique key `(user_id,event_id,role_key,institution_scope)` does not consider `revoked_at`. Revoked grant blocks a future **new** grant with identical scope. Correct via a bounded **new forward migration** with active-only uniqueness plus regression tests; no schema wipe, no deletion or original migration rewrite.
- Existing `GO CODING ICH-S0-02` covers this limited correction by Codex VS Code. **STOP before push/PR/merge/deploy**; owner publication consent comes only after follow-up independent review. No new S0-03/S0-04 implementation.

## 21K-02 — REV-03 independently revalidated and CLOSED locally (28 October 2026)

- [21K-02 independent final local review](21K-02-S0-02-REV03-FINAL-LOCAL-REVIEW.md): Codex feature branch `feat/ich-s0-02-db-foundation` exact HEAD **`f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`**, parent `78bc6d9`, clean. REV-03 changes **3 files (+97/-0)**; original first migration preserved, test +41 and append-only log +33.
- New forward migration changes generated active-only `institution_scope` to nullable `CASE WHEN revoked_at IS NULL THEN COALESCE(institution_id,'') ELSE NULL END` and keeps current four-column unique index; regression checks revoke/regrant twice, old revoked history, duplicate active denial and cross-scope isolation.
- **Independently reran** MySQL DB integration **8 PASS / 134 assertions**, existing DB-free PHP smoke **4 PASS / 36 assertions**, Pint **29 files PASS**, Composer, TS typecheck, Vite build all PASS; migration status **batch 1+2 Ran**. Read-only post-test SQL proves all four domain tables **zero rows**, migrations=2, actual stored generated column/index correct.
- **REV-03 REVIEW_PASS / LOCALLY CLOSED**, with deliberate non-reversible corrective `down()` safety limitation documented. No new Linux CI on S0-02 feature SHA; feature not pushed. Owner must separately approve **Codex-only normal push exact SHA `f4eb25c...`**, after which ChatGPT may open PR and audit GitHub Actions. **No merge/deploy/DB wipe/new code task authorized**.

## 21K-03 — Owner accepts REV-03 and authorizes exact Codex push (28 Oct 2026)

- **OWNER_EXPLICIT_ACCEPT:** `ICH-S0-02 REV-03` independently passed [21K-02 review](21K-02-S0-02-REV03-FINAL-LOCAL-REVIEW.md). Owner explicitly authorized **Codex VS Code ONLY** to perform **normal non-force push** of branch `feat/ich-s0-02-db-foundation`, **exact SHA `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`**. ChatGPT may subsequently create PR and inspect actual Ubuntu PHP8.4/Node22 GitHub Actions. **No MERGE / DB reset / coding additional tasks / deployment.**
- [21K-03 exact publish handoff](21K-03-S0-02-OWNER-ACCEPT-CODEX-PUSH-HANDOFF.md) documents preflight/stop. Local verified `feat/ich-s0-02-db-foundation` HEAD exact `f4eb25c`, parent `78bc6d9`, clean, canonical origin; GitHub remote feature ref **NOT FOUND** before push. Original 13 S0-02 file paths plus one corrective migration = **14 scoped paths** in final feature diff from approved foundation baseline, no secrets.
- **Critical SHA preservation:** `main` now contains docs-only governance commits after feature baseline `3ad72cf...`. **Do not merge/rebase/fast-forward feature branch against main before push**, since changing feature HEAD would void exact-SHA permission. Normal push publishes current history as-is, PR will compare against latest main.
- Owner's subsequent authorization permits **ChatGPT to open PR** after confirming exact GitHub remote SHA, and inspect new CI. The published SHA has **no Linux CI result until an actual workflow run**. Merge requires separate approval.

## 21K-04 — S0-02 published; PR #6 two real CI runs PASS (28 October 2026)

- **OWNER APPROVED PUSH ONLY** of `feat/ich-s0-02-db-foundation` at exact `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`. Codex completed normal push, GitHub remote independently verified identical. [PR #6](https://github.com/akhmadafnan/iches-unisya/pull/6) created by ChatGPT under Owner's explicit prior permission: **OPEN / UNMERGED**, 2 feature commits, **14 reviewed paths**, no credentials or unrelated CI change, `mergeable_state=clean`.
- Actual CI **both SUCCESS** on exact SHA: [push run 40695877645](https://github.com/akhmadafnan/iches-unisya/actions/runs/40695877645), [PR run 40696398403](https://github.com/akhmadafnan/iches-unisya/actions/runs/40696398403). Steps PHP **8.4.27**, Laravel **13.39.0**, Composer validation and locked install, **4 DB-free tests/36 assertions**, Pint **29 files**, Node22/npm ci/TS typecheck/Vite **7.3.7** build all passed.
- Local authenticated MySQL checks from previous [21K-02](21K-02-S0-02-REV03-FINAL-LOCAL-REVIEW.md) independently PASS **8 DB integration tests/134 assertions** with transaction rollback and real generated-column/index inspection in owner-only `iches_unisya`. **Do not claim MySQL tests were run by GitHub CI** (CI remains DB-free).
- Full [21K-04 PR/CI gate report](21K-04-S0-02-PR6-LINUX-CI-PASS-AND-MERGE-GATE.md). **Owner merge approval NOT granted**; no merge, migrations/DB reset, other coding or deployment. Design Skills PR #3 remains separate.

## 21K-05 — ICH-S0-02 PR #6 merged and COMPLETE (28 October 2026)

- **OWNER EXPLICIT MERGE GO** for [PR #6](https://github.com/akhmadafnan/iches-unisya/pull/6), exact accepted feature HEAD `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`. Standard SHA-pinned GitHub merge returned `merged=true`, merge commit **`3eb1be7b23a2fd08fdcbd84e5fd1ba0819f90201`**. Independently verified PR **CLOSED/MERGED** and merge commit as `main` HEAD before docs-only postmerge recording, with parents `12512bb35c6e647488f7b9345cc9c74a2cb130aa` and exact `f4eb25c...`.
- **Exactly 14 approved changed paths**, two feature commits. Both real [push CI 40695877645](https://github.com/akhmadafnan/iches-unisya/actions/runs/40695877645) and [PR CI 40696398403](https://github.com/akhmadafnan/iches-unisya/actions/runs/40696398403) completed **SUCCESS**, PHP 8.4.27, locked Composer, DB-free Laravel 4 tests/36 assertions, Pint29, Node22/npm/TS/Vite. Owner local MySQL `iches_unisya` 8 tests/134 assertions separately validated; **not run in GitHub CI**.
- Final checks before merge: `mergeable=true`, `mergeable_state=clean`, `main` unprotected, required check list empty, no applicable active rulesets; normal GitHub merge without bypass. Detailed branch protection endpoint 403 (integration lacks admin), but readable branch summary/rulesets showed no required rule.
- [21K-05 merge closure report](21K-05-S0-02-MERGED-COMPLETE.md). **ICH-S0-01, ICH-S0-07 and ICH-S0-02 COMPLETE**, no additional coding GO, migration, database reset, or deployment. **ICH-S0-03 passwordless OTP remains NO_GO**; Design Skills PR #3 stays OPEN/UNMERGED. No automatic production cutover.

## 21K-06 — Dev demo accounts simplified by Owner (28 October 2026)

- **DEVELOPMENT FIXTURE POLICY FINAL:** [21K-06](21K-06-DEV-DEMO-USER-SEED-POLICY.md). All synthetic seeded user emails use **`@iches.test`**; when password-based local demo login exists, shared demo credential is **`password`**, stored as a hash, **never plaintext in the DB**. Predictable values only in local/test environments; no production demo login or weak accounts.
- **Existing S0-02 users table has NO password column**, and **participant login stays email OTP / Magic Link (NEW-003, RD-06)**. Do not silently implement participant password login. If Codex later needs demo password login, do it as local-only opt-in within a newly authorized S0-03 slice; otherwise use local mail logs/inbox for OTP. Idempotent non-destructive seeder only; no schema wipe.
- **S0-03 still NOT GO CODING.** This decision removes repeated fixture design discussions; add to next auth Work Order, no immediate code, DB migrations, data insertion, PR, merge or deployment. S0-01/S0-07/S0-02 remain COMPLETE; PR #3 still separate.

## 21K-07 / 21K-08 — S0-03 Authentication MVP implementation-ready (28 Oct 2026)

- Owner asked to continue concretely and comprehensively. [21K-07 full auth & demo UAT](21K-07-S0-03-AUTHENTICATION-MVP-SPEC.md) + [21K-08 bounded executable Codex Work Order](21K-08-S0-03-CODEX-WORK-ORDER.md) are **PREPARED**. Per [20B explicit authorization rule](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md), generic “lanjut” **does not equal named GO**; next valid Owner authorization is **`GO CODING ICH-S0-03`**. No code, migration, seeding, push, PR, merge or deploy was performed by ChatGPT.
- Vertical MVP: participant **OTP / Magic Link passwordless** (10m, 5 attempts, one use, hashed tokens, throttling, generic responses), Laravel session guard and CSRF, email log local, minimal login/dashboard/logout. Dev-only `@iches.test` accounts with **`password` stored hashed**, nullable password via new additive migration, explicit repeat-safe synthetic seeder; **local environment + demo flag double gate**. Preserve real policies and UUIDv7.
- Concrete existing-source finding: `config/auth.php` not yet present, no auth routes, `users.password` absent; `config/session.php` and mail config exist, Foundation landing at `/`. Read-only desktop preflight found **clean stale S0-02 feature** and stale local `origin/main`; Codex should safely sync merged main before new `feat/ich-s0-03-passwordless-auth` branch.
- No S0-04 RBAC broad implementation or PR#3 change; after named S0-03 GO Codex can implement the entire bounded slice without repeated per-step approvals, commit local, then stop before publishing.

## NOW — satu prioritas yang boleh aktif
**NOW: ICH-S0-03 AUTH_VERTICAL_SLICE_SPEC_READY / NAMED_GO_PENDING / NO_CODE.** 21K-07 architecture/UAT and 21K-08 executable Codex Work Order complete; dev @iches.test + hashed password opt-in, attendee OTP/Magic Link unchanged. S0-01/S0-07/S0-02 MERGED; no source/DB changes until exact GO CODING ICH-S0-03.

| Work item | Status | Bukti / blocker saat snapshot | Next action / penanggung jawab |
|---|---|---|---|
| **ICH-PRE-01** Audit blueprint dan repo lama | **REVIEWED_DOCS** | 18C-05A & 19A–D tersedia; tes runtime legacy belum diulang | Revalidate reuse per task, ChatGPT |
| **Workspace governance 19E/19F** | **DRAFT_REVIEW** | Dokumentasi operasi dan papan kerja diajukan di branch docs | Owner review; ChatGPT perbaiki bila perlu |
| **ICH-PRE-02** Keputusan teknis RD-01..06 | **APPROVED_FINAL** | Owner: “Setuju paket 20D”; tercatat di Register 09, termasuk OTP/Magic Link | No remaining RD-01..06 decision blocker; verify test DB G0 |
| **ICH-PRE-03** Codex workspace/Skills | **READ_ONLY_HANDSHAKE_PASS** | Codex confirmed canonical remote, clean docs branch, full reading of AGENTS/PLANS/board and 4 Skills; MySQL97 Running / 3306 TCP reachable | PRE-03 Codex check complete; test DB validation remains G0 gate |
| **ICH-PRE-04** GO CODING task atau sprint pertama | **NO_GO** | Tidak ada otorisasi implementasi | Setelah PRE-02/03 green, owner memberi GO Task ID atau named Sprint ID sesuai Work Order |
| **ICH-S0-01** DB-free Laravel/Vue Foundation | **MERGED / COMPLETE** | [PR #4](https://github.com/akhmadafnan/iches-unisya/pull/4), accepted `8eba7cb5` merged at `561baf322d`; local audit 21E PASS, CI not configured | Preserve source; next task needs named GO |

**Override terbaru (21I):** `ICH-S0-01` sudah independen PASS dan MERGED/COMPLETE; catatan NO_GO/PENDING sebelumnya adalah snapshot historis.

## NEXT — antrean, bukan izin mengerjakan
1. **ICH-S0-01** bootable foundation + actual test evidence; setelah mendapat GO sendiri.
2. **ICH-S0-02/03/04/05/06/07** identity/role/storage/frontend/test foundation, dipilih berdasar dependency dan per-task GO.
3. **ICH-S1-01..08** public website/CMS/countdown; aspek UI tidak perlu owner mengunci komponen satu per satu, tetapi publikasi dan data event harus benar.
4. **ICH-S2-01..06** submission Text+PDF; final valid submission langsung reviewer index tanpa screening.

**Not now:** S3–S9, full bank verification, QR offline, OJS handoff, bank credential, real participant migration, deployment. Keputusan produknya tetap ada di blueprint, bukan dibatalkan.

## Decision bundle RD-01–RD-06 — APPROVED FINAL; RD-07–RD-08 technical detail pending
| Kode | Rekomendasi | Status |
|---|---|---|
| RD-01 | `akhmadafnan/iches-unisya` canonical, selective reuse old repo | **APPROVED FINAL 20D** |
| RD-02 | Laravel 13, PHP 8.4 target; host verified later | **APPROVED FINAL 20D / HOST UNKNOWN** |
| RD-03 / RD-03C | MySQL/InnoDB, single development DB **`iches-unisya`** manually created in phpMyAdmin; root local temporary; second test DB unnecessary | **OWNER LOCKED 21J-02 / DB EXISTENCE NOT VERIFIED / S0-02 NO_GO** |
| RD-04 | UUIDv7 IDs and consistent FKs | **APPROVED FINAL 20D** |
| RD-05 | Laravel Policies/Gates + role/event/institution/resource scopes | **APPROVED FINAL 20D** |
| RD-06 | Session-backed participant OTP/Magic Link **passwordless**; staff mode later | **APPROVED FINAL 20D** |
| RD-07 | One-user Switch Workspace, audited read-only Support View | **FUNCTION APPROVED / TECH PROPOSED** |
| RD-08 | Laravel typed-block versioned CMS | **FUNCTION APPROVED / TECH PROPOSED** |

Sumber detail: [18C-05 decision gates](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md). **RD-01–RD-06 recorded APPROVED in 09 and board; RD-07/RD-08 technical details remain separate.** Jam resmi acara belum ada; countdown tidak boleh mengarang 00:00.

## Template update board (ChatGPT only)
- **Date/time, branch/HEAD, task**:
- **Status before → after**:
- **Authorization evidence**:
- **Scope, PR, tests actually run, relevant negative cases**:
- **Unresolved blocker / responsible person**:
- **One next action**:

## Fast handoff ke chat baru / agent
**ChatGPT:** baca `WORKSPACE.md`, board ini, 19E, kemudian 09/19B/19C sesuai Task ID. Review diff dulu sebelum mengubah status.  
**Codex:** baca `AGENTS.md` + skill engineering + **work order ber-GO**; jika belum GO, inspection/read-only saja.  
**Owner:** cukup kirim ID tugas, link branch/PR, dan output tes. Tidak perlu menyalin seluruh blueprint.

## Catatan manajemen target dua hari
Target 48 jam adalah **timebox sejak GO coding dan environment siap**, bukan deadline pasti untuk seluruh S0–S9. Prioritas rilis selalu keamanan submission/finance dan CMS yang benar, bukan angka persentase semu.
