# 20C-01 — ICH-SPRINT-01: Foundation Bootstrap & Verification (DRAFT)

> **UPDATE 28 OCT 2026 — SETUP AUTHORIZED & COMPLETED; NO GO CODING.** On connected Windows desktop the owner-authorized canonical GitHub clone now exists at `D:\PINJAM-AFNAN\iches-unisya` (hyphen), remote `akhmadafnan/iches-unisya`, clean branch `docs/19e-agent-workspace` tracking origin, initial checkout SHA `432dcd012fa49b25019ad6464e7dc3759b99cd11`. The chosen VS Code at `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (space), v1.143.1, **opened the ICHES workspace**. OpenAI Codex extension `openai.chatgpt@26.51021.22027` is installed in the shared Windows user profile; **in-app sign-in/Skills loading NOT VERIFIED**. Four repo Skills plus AGENTS/PLANS verified. Git clone and GUI launch were done with user setup approval, **not application coding GO**. PR #1 is still draft/unmerged, and disposable MySQL DB plus technical decisions are pending. Earlier lines describing the target folder as absent or GUI as unlaunched are pre-setup historical observations.

> **STATUS: BLOCKED_DECISION / NO GO CODING.** This is a proposed finite **Sprint Work Order**, not a prompt that may yet execute. Owner consent to plan/workflow does not authorize changing source or installing packages. Use [20B](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md).

## Authorized scope proposal

| Field | Required value |
|---|---|
| Sprint ID | `ICH-SPRINT-01` |
| Ordered task IDs | `ICH-S0-01` → `ICH-S0-02` → `ICH-S0-07` (**three only**) |
| Business goal | Minimal, bootable Laravel/Inertia/Vue foundation with canonical identity/event schema and repeatable test pipeline |
| Repository | Proposed `akhmadafnan/iches-unisya`; owner must confirm RD-01 before READY |
| Owner-selected local workspace path | `D:\PINJAM-AFNAN\iches-unisya` (**clone completed**, clean docs branch tracking origin) |
| **Owner's VS Code** | `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (version **1.143.1**, with space). Separate 1.128.0 install under hyphen path is **not the chosen default**; Codex extension/sign-in on chosen install not verified |
| Start baseline | Approved merge of governance PR and verified `main` commit; **not frozen yet** |
| Branch | `feat/ich-sprint-01-foundation` (to be created by Codex **after** named GO) |
| PR | One tightly scoped foundation PR may include three ordered tasks; individual per-task commits and evidence entries; **no auto merge** |
| Authorization | **NONE**. Owner must issue separate explicit `GO SPRINT ICH-SPRINT-01` after READY |
| Test environment | Disposable local/test MySQL DB only, not yet verified |
| Scope outside | No website CMS, countdown, Abstract, Finance, Co-Host, real users, bank/OJS, production deploy |
| Logging | Codex append-only `harness/build-log.md` and sanitized `harness/evidence/**` **after GO** |

## Source reading and Skills
Always read `AGENTS.md`, the brief `PLANS.md` roadmap and the single current board 19F. Task ID details stay in 19B; use 19C template + 19D traceability. Load `iches-codex-engineering` + `iches-code-verification`; public/frontend and Admin Skills are **not needed** for foundation work unless implementation's scoped change requires them. Read 18C-05A selective reuse audit, 18C-05 readiness decisions, 18C-01/01A data dictionary (for S0-02), and 18C-05B tests **selectively** (2–4 relevant sources per active task).

## Gate per task (no shortcuts)

### ICH-S0-01 — selective foundation
- IN: Laravel 13/Inertia Vue3/TS/Tailwind4 bootstrapping, locked compatible dependencies, verified audited technical foundation reuse only, base app entry/config/tests. Only relevant source/manifest files.
- OUT: old payment verification, registration business domain, production data and .env, business-specific migrations.
- ACCEPT: local app boots, actual Composer/npm install and frontend build evidence; provenance documented in handoff; no secrets and no unrelated files.
- NEXT: only if tests PASS and task evidence appended; proceed to S0-02 if database is approved/test-ready.

### ICH-S0-02 — identity/event schema
- IN: canonical approved identity/event models and migrations with UUIDv7 (only **if RD-04 approved**), safe test fixtures and constraints.
- OUT: implementing 53 ERD candidates, premature Finance/CMS/Academic tables, destructive production data changes.
- ACCEPT: isolated test-DB migrate/rollback, FK/unique/UUID negative checks, event hour remains nullable until official; no fabricated event start hour.
- NEXT: only after PASS evidence; then S0-07.

### ICH-S0-07 — repeatable quality pipeline
- IN: task-scoped PHP feature smoke tests, typecheck/build/lint commands, reproducible CI on branch/PR where allowed.
- OUT: CI with production credentials, claiming UAT or finance/security suites not run.
- ACCEPT: actual command exit codes, failures and limitations documented; negative test case from schema/authorization where applicable; no "green" without a run.

## Allowed file boundaries (after GO)

`app/**`, `bootstrap/**`, `config/**`, `database/**`, `routes/**`, `resources/js/**`, `resources/css/**`, `tests/**`, `composer.json`, `composer.lock`, `package.json`, npm lockfile, build/typecheck/test configuration and `.github/workflows/**` **only as needed for listed tasks**. Allowed evidence path `harness/build-log.md` **append-only** and sanitized `harness/evidence/**`.

Never edit `AGENTS.md`, `PLANS.md`, `WORKSPACE.md`, `README.md`, `docs/**`, `.agents/skills/**`, owner decisions, secrets or production data. No branch force rewrite, automatic merge or deployment.

## READY blockers (all must be cleared)
- [ ] [Governance PR #1](https://github.com/akhmadafnan/iches-unisya/pull/1) owner-reviewed/merged, local clone synced.
- [ ] RD-01 canonical repo and legacy reuse selection approved.
- [ ] RD-02 PHP + Node/dependency compatibility approved, backed by actual environment check.
- [ ] RD-03 MySQL test DB verified; RD-04 UUIDv7 FK policy approved.
- [ ] RD-05 RBAC identity model and RD-06 baseline auth approved as needed for schema.
- [ ] Owner's VS Code 1.143.1 executable located (space path), but CLI `new_code.cmd` wrapper failed and Codex sign-in/Skills remain unverified. **Target `D:\PINJAM-AFNAN\iches-unisya` (hyphen path) is absent**; await setup authorization before clone/remote checks.
- [ ] ChatGPT provides final Work Order base commit + applicable scripts/test cases.
- [ ] Owner sends explicit GO SPRINT **after** READY.

**Cut line for two-day aspiration:** do not expand this sprint to S1 or S2 implicitly. Prioritize reproducible S0 PASS; schedule a separate bounded ICH-SPRINT-02 for Auth/Backoffice/CMS after this sprint is reviewed.

**STOP** on any unapproved DB/PK/auth assumption, unmet test, out-of-scope feature, real bank/participant data, risky migration, credentials or production operation. Report BLOCKED truthfully.
