# 21L-01 — ICH-S0-04 Scoped Authorization & Switch Workspace (Codex Work Order, PREPARED)

> 28 October 2026 — **PREPARED / NO_GO_CODING**. ChatGPT documentation only. **Dependency:** ICH-S0-03 PR [#8](https://github.com/akhmadafnan/iches-unisya/pull/8) has a successful PHP 8.4/Node 22 check on head `6b32e7580fb04fb789908a5f486d11d1bfa24adb`, but remains **OPEN and NOT merged**. Owner must separately authorize merge and then **GO CODING ICH-S0-04**. This document is not authorization for Codex, migrations, seeding, push, merge or deployment.

## Owner-locked design and product invariants

- **Do not use the COMING SOON flyer for website styling.** It is only an information/reference source for candidate speakers, agenda, theme, institutions. Locked frontend: Inter + Sora, ivory `#F7F6F1`, forest `#164734`, near-black `#17211C`, gold `#B79A5B`; academic-editorial, responsive, no carousel, EN/ID/AR RTL. [Brand source/precedence](brand/README.md). S0-04 has **no authority to redesign public/frontend CMS**.
- **One account (UUIDv7), multiple roles and scopes**; switching workspace keeps the **same authenticated `user_id`**. Never create per-role duplicate accounts; session selection is only a UI preference and **never an authorization grant**.
- For dev fixtures, `@iches.test` and shared hashed `password` remain strictly local-only, according to [21K-06](21K-06-DEV-DEMO-USER-SEED-POLICY.md); production participants remain passwordless OTP/Magic Link.
- **Backend remains authoritative** for event, institution, owner and resource access; role revoked during a session immediately loses access. Future bank setting changes remain **Superadmin-only**, no second approval; do not invent bank/payment data or implement Finance in S0-04.
- Reference approved principles, not unconfirmed role names: [18A role blueprint](18A-ROLE-PERMISSION-DAN-NAVIGASI-BACKOFFICE.md), [18C-03 RBAC draft](18C-03-DRAFT-AUTHENTICATION-RBAC-SECURITY-ARCHITECTURE.md), [18C-03A security cases](18C-03A-DRAFT-PERMISSION-MATRIX-SECURITY-UAT.md), [18C-03B workspace/support distinction](18C-03B-DRAFT-SWITCH-ROLE-WORKSPACE-AND-USER.md), [19B backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md). Earlier drafts are **not automatically an approved exhaustive technical role catalog**.

## Existing source baseline, to verify after S0-03 merge

- `users`, `events`, `institutions`, `role_assignments` exist; assignment has `user_id`, `event_id`, nullable `institution_id`, `role_key`, `assigned_by`, `revoked_at`, active-only uniqueness. `RoleAssignment` is mass-assignment guarded, with `active()`, `forUser()`, `forEvent()`, `forInstitution()`. `UserPolicy::view` self-only; `EventPolicy::view` active event grant; `InstitutionPolicy::view` active grant for matching event + institution.
- S0-03 introduces session web auth and `/dashboard` login/logout, but **does not establish comprehensive role-switch authorization**. Preserve its OTP, Magic Link, session middleware and local demo behavior exactly.

## Bounded implementation scope — one small vertical slice

1. **Scoped authorization service:** derive current user capabilities from *active assignments* in the requested event/institution; explicit allow-list for the few implemented role types, deny unknown roles by default. Use Laravel Policies/Gates; do not install a third-party permission system merely to replicate an existing simple table. No role-key-to-superadmin escalation from display names or `@iches.test` domains.
2. **My own workspaces:** return server-derived eligible workspace choices (always Personal; event/institution/work roles only if current user actually assigned), with safe labels and stable context identifiers. A user with two event grants can choose each without cross-event leakage. Session active-workspace preference is validated **on every request** and falls back safely to Personal when expired/revoked or scope invalid. Never change `Auth::user()`.
3. **Minimal UX:** small, accessible Switch Workspace control within existing authenticated dashboard/header using current Vue3/TypeScript/Inertia; current identity stays visible. Do not start new backoffice/sidebar/CMS or overhaul public page.
4. **Testable guard endpoints:** demonstrate one protected event-scoped endpoint and one institution-scoped endpoint with real server authorization and negative tests (403/404) for guest, wrong user, wrong event/institution, revoked assignment, tampered context. Avoid creating hypothetical Paper/Session tables before their separately authorized modules.
5. **Read-only support-view preparatory boundary:** Owner approved the *direction* of Superadmin-only View as User (read-only, audited, actor retains identity). This S0-04 vertical slice shall **not** implement full impersonation, `Auth::login($target)`, privileged support session, or broad user-search UI. Specify deferred isolated implementation once the exact permissions/audit/display contract is approved; no mutation, bank, Finance, reviews or file download via support preview.
6. **Future permission stubs:** express explicit fail-closed tests for bank-settings Superadmin-only (without bank settings persistence/UI), no Finance Verify or academic decision routes. Keep existing policies and UUIDv7 intact; forward-only additive migration **only if genuinely unavoidable**, independently gated by owner; prefer zero new tables.

## Security acceptance tests (must be actual, not asserted by claims)

- A user with multiple active event roles switches workspace **without logout or identity change**, and only to scopes they own.
- Wrong event, wrong institution, missing grant, forged workspace key, revoked grant, guest and unknown role are denied on the next request; stale browser tab cannot bypass policies.
- Only session-scoped workspace preference changes; switching does not create/regrant assignments or modify ownership; no self-promotion to Admin/Superadmin.
- One event-wide grant **never** grants institution-wide access; event/institution policy restrictions remain; no leaked unrelated email/personnel data in Inertia props.
- Hidden menu is not the security boundary: direct HTTP requests to guarded endpoints must fail correctly.
- Existing DB-free PHP CI remains green, plus opt-in MySQL tests only against exact owned `iches_unisya` with transaction rollback; no `RefreshDatabase`, migration reset or truncation.
- Responsive keyboard-operable switcher, no invented workshop/role labels exposed as official, no user impersonation.
- S0-03 auth tests, Pint, TypeScript, Vite, and GitHub Actions PHP8.4/Node22 remain green.

## Codex execution protocol (ONLY AFTER NAMED OWNER GO)

- Confirm **PR #8 merged**, current `main` SHA, clean worktree and safe fast-forward. Create isolated `feat/ich-s0-04-scoped-workspaces` branch from latest `main`; stop if divergence/dirty.
- Codex reads this document, `AGENTS.md` and existing role models/policies once. Inspect only affected source and tests; no large blueprint scan, no subagents and no repetitive full builds. Implement backend guards/test-first, then small UI. Run targeted tests during work and one final suite.
- Allowed after GO: narrowly scoped `app/**`, `routes/web.php`, `resources/js/**`, `tests/**`, optional new additive migration *only after explicit DB gate*, and append-only sanitized harness evidence. Do not edit prior migrations, CI, package manifests, unrelated docs, real bank/finance or site CMS.
- Produce a **local commit only** and short exact handoff (SHA, paths, assertions, test results); stop **before push/PR/merge/deploy/DB resets**. ChatGPT independently reviews source and evidence.
- No more passwords/security hurdles for local developer accounts beyond existing S0-03 controls. S0-04 must not require paid packages, SMTP, production MFA, or a new DB to demonstrate the local UX.

**Current next owner decision:** `GO MERGE PR #8 ICH-S0-03` after final check. The later `GO CODING ICH-S0-04` is separate and not implied by a general “lanjut”.
