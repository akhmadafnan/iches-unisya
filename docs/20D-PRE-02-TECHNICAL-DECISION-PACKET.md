# 20D — PRE-02 Technical Decision Packet / Sprint 01

> **28 October 2026 · OWNER APPROVAL REQUEST — PROPOSED, NOT AUTOMATICALLY FINAL.** This document consolidates six choices into one bounded package. [19F](19F-CURRENT-SPRINT-BOARD.md) is the only active status board. No specific coding/sprint GO has been issued.

## Verified readiness (PRE-03)
- Codex VS Code read `AGENTS.md`, `PLANS.md`, 19F and **all four repo Skills** directly in `D:\PINJAM-AFNAN\iches-unisya`. Correct remote, docs branch, clean Git worktree; **read-only handshake PASS**. This demonstrates file access, not automatic Skill selection.
- Windows environment: PHP **8.4.24**, Composer **2.10.1**, Node.js **26.4.0**, npm.cmd **11.17.0**, Git **2.55.0**; chosen VS Code **1.143.1**.
- Windows `MySQL97` service **Running**; server binary metadata **9.7.1.0**, TCP `127.0.0.1:3306` reachable. **No DB authentication, isolated test schema, InnoDB SQL check, Composer install or app tests yet.**
- [Draft PR #1](https://github.com/akhmadafnan/iches-unisya/pull/1) unmerged; live source is still a documentation-only repository.

## Recommended owner decision bundle — RD-01 through RD-06

| ID | Proposed choice for first sprint | Guardrail / limitation |
|---|---|---|
| **RD-01** | Canonical repo `akhmadafnan/iches-unisya`. Legacy repo used only for **reviewed technical selective reuse** | Never copy legacy payment/screening workflows or secrets. Actual clone and workspace selection already done |
| **RD-02** | **Laravel 13 + PHP 8.4** target; local **Node 26** for evaluation, with exact frontend dependency versions locked by Codex after GO | Framework and packages must actually resolve/build; hosting runtime remains a later production gate. Do not force Node 26 if dependency compatibility fails |
| **RD-03** | **MySQL/InnoDB** with transactions/FKs/JSON; use **isolated disposable test DB only** in Sprint 01 | Running MySQL 9.7.1 and reachable TCP are not proof of authenticated DB connection or compatibility |
| **RD-04** | **UUIDv7** as canonical app/entity IDs and consistent FK relations | Migration/table/foreign key representation must be decided consistently in implementation; no mixing with BIGINT casually |
| **RD-05** | **Laravel Policies/Gates with role, event, institution and resource scopes**; one user can hold multiple roles, switching workspace without changing `user_id` | Sidebar hiding is not security; support 'View as User' stays strictly read-only, audited; do not assume full impersonation |
| **RD-06** | **Laravel session-backed authentication**, but **participants use passwordless Email OTP / Magic Link** per **approved NEW-003**. Staff credential/MFA mechanism is separately configurable and must not silently impose participant passwords | Earlier 18C-03 / 18C-05 proposal of participant **email/password** conflicts with approved NEW-003. Reconcile this in architecture and schemas before creating auth UI |

**Decision status:** every row here is **RECOMMENDED FOR OWNER APPROVAL**; a recommendation is never a coding GO. If owner approves all six, ChatGPT updates the Decision Register; if owner revises a row, record only that variance. Details such as email delivery provider, OTP expiry/rate limiting, staff MFA, live hosting and domain remain explicit later implementation/operations gates, not permission to invent behavior. **M1 Sprint 01 is foundation/schema/test only, not a complete login implementation.**

## Required operational checks after decision approval

1. Review/merge the governance documentation PR with owner authorization and sync the local Git checkout; never auto-merge.
2. Identify a safe local/test MySQL account and disposable database **without collecting secrets in chat or GitHub docs**; check authenticated server version, InnoDB, FK support and migration permissions on **test environment only**. Do not create/drop databases just to perform a read-only preflight.
3. Freeze Sprint Work Order `ICH-SPRINT-01` source SHA, validation commands, and ordered Task IDs `ICH-S0-01 → ICH-S0-02 → ICH-S0-07`.
4. Owner sends a **separate, explicit** `GO SPRINT ICH-SPRINT-01` after READY. Codex then implements source on approved feature branch. No automatic merge, deployment or production action.

## Decision statement for owner (not yet received)

`SETUJU RD-01 s.d. RD-06 SESUAI 20D`

This would approve the technical decisions only, **not** GO Coding or PR merge.
