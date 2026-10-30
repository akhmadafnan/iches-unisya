---
name: iches-codex-engineering
description: Implement, repair, refactor and verify ICHES UNISYA 2027 Laravel/Vue application CODE in Codex VS Code against an owner-authorized task ID and work order. Use for any ICH-S0..S9 coding task, migrations, tests, UI source, build configuration, integration fixes or debugging. Keep GitHub product docs/governance edits with ChatGPT.
metadata:
  project: iches-unisya
  workflow: codex-vscode-code-only
  authorization: requires-explicit-owner-go
---

# ICHES Codex Engineering — Code-only Skill

## CURRENT EXECUTION GUARDS — 30 October 2026

**Owner exclusively runs Codex VS Code after explicit named GO CODING**; ChatGPT does not launch Codex (CLI, remote or VS Code) and retains governance/docs/review responsibility under separate permission. Current completed: S0-01..07, UI-01B/01C/01D, S1-01..04. S1-05 is audit/plan only, not authorized coding. Baseline post-S1-04: `99aa3e83306f92b72c13c99c16b6099e583ebf26`, subject to live SHA preflight. Development schema `iches_unisya`, isolated CMS test `iches_unisya_cms_test`; database writes/migrations/seeding/rollback require explicit separate permission.

**Required gates:** AUDIT → PLAN → OWNER GO CODING → owner-operated CODEX VS CODE → INDEPENDENT REVIEW → GO TEST if needed → GO PUSH & PR → CI REVIEW → GO MERGE → POST-MERGE SYNC. Never auto-advance from merged or prepared tasks. Public = CMS-driven Academic Editorial; Admin = shadcn-vue Lyra V1.1 `a33J5lFw`; Laravel 13/Inertia/Vue 3/TS/Tailwind 4, scoped authorization/private storage, EN/ID/AR RTL are retained.

**Permanent UAT override:** Local Browser on real Herd `.test` Laravel app and development data; report URL/scenario/expected/observed/PASS-FAIL-BLOCKED/findings in text only. NO newly generated screenshots, PNG/JPG/WebP or image-evidence folders; prior captures retained. Synthetic fixtures are tests, not live browser UAT.



**Scope:** You are the **source-code implementer** in **Codex VS Code**. The owner uses **ChatGPT** for analysis, requirements, architecture, documentation, planning, review and decisions; **GitHub** is the shared record; terminal is used for authorized tests/inspection. This skill **does not authorize coding** merely because it exists.

## 21G optional specialized methodology Skills

Supplemental pinned skills `ponytail`, `ponytail-review`, `ponytail-audit`, `diagnosing-bugs`, `tdd` may be consulted **only when task-relevant** and never supersede ICHES native instructions, owner-approved Work Order, or named GO. Consult [21G routing](../../../docs/21G-01-SKILLS-ROUTING-POLICY.md). They do not authorize self-starting coding, global persistence, script execution, DB migrations or merge/deployment. Reviews/audits are read-only, and any third-party `Ship` is not owner acceptance. For TDD, seams already specified in an approved Work Order need no repeated user confirmation.

## Preflight — REQUIRED every coding task

1. Confirm owner explicit **GO CODING Task ID or GO SPRINT for finite named ordered Task IDs including this task**, with complete Work Order. Consent to governance or “continue” is NOT GO. If no named GO: do not edit code or journal. See `docs/20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md`.
2. Verify `git remote -v`, branch, `git status --short`, current HEAD, actual source paths. **Owner-approved RD-01..06:** canonical `akhmadafnan/iches-unisya`, Laravel 13/PHP 8.4, MySQL/InnoDB, UUIDv7, scoped Policies and participant OTP/Magic Link without password. Actual Windows repo is `D:\PINJAM-AFNAN\Herd\iches-unisya`. `ICH-S0-01` DB-free foundation uses [21B](../../../docs/21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md); schema changes still require isolated test DB and separate GO. **Never work inside old `international-conference-platform` as if it were target**.
3. Read root `AGENTS.md`, only the pertinent owner decisions from `docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md`, and the exact Work Order from `docs/19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md`/approved prompt. Consult `docs/19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md` and `docs/19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md` as needed.
4. If task involves public frontend/Website CMS/countdown, **also read** `.agents/skills/iches-public-frontend/SKILL.md` and focused references. Component layouts **are not locked**; Academic Editorial, clean modern, no AI slop, no carousel, EN/ID/AR RTL.
5. If the work order lacks a crucial owner-approved DB/PK/business decision, **stop and report exactly which decision blocks coding**; do not invent it.

## Strict edit boundary

**You MAY edit only application CODE files** explicitly needed for the current Work Order: Laravel PHP app/route/config/bootstrap, database migrations/seeders, Vue/TypeScript/CSS source, `tests/`, scripts, dependency manifests/lockfiles and automation/CI configuration necessary for the task. Create code files in a dedicated feature branch, **never directly main**.

**You MUST NOT edit** `PLANS.md`, `WORKSPACE.md`, `README.md`, `docs/**`, `AGENTS.md`, `.agents/skills/**`, governance/design decisions or policy documents. **Only named-GO Markdown exception:** append to `harness/build-log.md` preserving previous entries, and sanitized evidence under `harness/evidence/**`. Report doc changes to ChatGPT, never change rules to make tests pass.

Never copy or commit `.env`, secrets, personal participant records, payment proofs, bank credentials, production database, official unapproved logos, or email/password tokens. Never run production migrations, release/deploy, force-push, delete/rewrite branches, or merge PRs unless separately authorized for that action.

**Codex may read blueprint docs and run tests/terminal commands as needed for code changes.** This doesn't change responsibility for authoring docs.

## Engineering method

- **One Task ID = one business objective.** Advance to next task only if explicitly listed in owner-approved Sprint Work Order, dependencies are ready, tests actually PASS and evidence is logged. Otherwise STOP.
- Use reviewed selective reuse from legacy Laravel repository only after examining actual source and compatibility. **Never port legacy payment verification** unchanged: ICHES Finance requires actual bank mutation match, not proof upload alone. Do not port old administrative abstract screening.
- Backend: Laravel FormRequest + Policies/Gates + focused Actions for state-changing transactions; immutable finance/academic history; concurrency/idempotency checks; afterCommit for notifications.
- Frontend: Inertia + Vue 3 + TypeScript + Tailwind 4 + shadcn-vue (Backoffice), accessible responsive semantic UI. Avoid one giant page, fake content, unsafe arbitrary HTML or CSS from CMS.
- Keep server authoritative for roles, deadlines, billing, academic decisions and publication status; do not trust `active_workspace` as permission. User identity remains the same while switching own roles; Superadmin View as User is **read-only** and retains real actor.
- Public site must have real **CMS create/edit pages and sections**, Draft/Preview/Publish, and a **four-unit countdown** from official Event Master timezone-aware time. **Official event start hour is not yet supplied**; never hardcode midnight.
- Abstract Text+PDF valid Submitted → directly eligible Reviewer Index. **No admin screening**; Published ACCEPT → Full Paper **regardless of payment**.
- Co-Host single institutional invoice (IDR 3m), five seats/papers after Verified and no duplicate presenter charge; personal package (IDR 1m) grants four activities after Verified.
- OJS export is only “ready” after adapter/target staging validation; selected for journal is not journal acceptance.

## Tests and evidence — do, don't just plan

Inspect actual `composer.json`/`package.json` scripts and environment before selecting commands. On authorized local/test resources run relevant:
- PHP tests (Pest/Feature), lint/static analysis; migration/rollback tests **only on a separately owner-authorized isolated test DB**, with exact allowed operations and no implicit GO from coding authorization.
- Vue/TS typecheck, build/lint, browser/mobile/Arabic RTL smoke when UI changes.
- Negative permission tests (IDOR/role scopes), business state/concurrency/idempotency as applicable.
- Countdown fake-clock boundary/timezone and CMS unpublished/published/rollback tests when relevant.

Use synthetic fixtures for automated tests only, never private participant or financial data. Real visual UAT requires the actual Local Browser/Herd `.test` development application and data, with text-only evidence. **If a test cannot run, report BLOCKED and reason**, not PASS.

## Hand back to ChatGPT & owner

Return concise **Task ID; branch; source files added/changed; what actually works; exact test commands/results; real Local Browser UAT text (URL/scenario/expected/observed/PASS-FAIL-BLOCKED/findings) if UI, no new screenshots; open risks/decisions; git status; commit/PR if made**. Append sanitized execution evidence to `harness/build-log.md` only on authorized branch under named GO. Identify documentation changes needed **in your message**, but don't edit governance docs. ChatGPT handles review; owner authorizes merge/production separately.

**Task is not “DONE” merely because source compiles.** Acceptance includes negative tests, approved scope and review.

## Relevant entry points

- [Work protocol](../../../docs/19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md)
- [Technical backlog](../../../docs/19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md)
- [Work Order template](../../../docs/19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md)
- [Source-to-code traceability](../../../docs/19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md)
- [Readiness gates](../../../docs/18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md)
