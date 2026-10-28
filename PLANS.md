# PLANS.md — ICHES 2027 Master Execution Plan

> DRAFT · 28 October 2026 · NO GO CODING. This is the ONE permanent roadmap, not a second status board. See [19F Board](docs/19F-CURRENT-SPRINT-BOARD.md) for current status, [19B Backlog](docs/19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) for task IDs, and [09 Decision Register](docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md) for product authority.

## Mission and fixed boundaries

Build The 6th ICHES 2027 using Laravel 13 + Inertia + Vue 3 + TypeScript + Tailwind CSS 4, with shadcn-vue Backoffice and original Academic Editorial public frontend. No Bootstrap or React/Next.js app replacement. Website CMS is editable from Backoffice, multilingual EN/ID/AR RTL; countdown targets the official event master start (hour still unconfirmed). Abstract Text+PDF valid submission goes straight to Reviewer Index without administrative screening. Finance verification requires matching **real bank mutation**, not a screenshot alone. Selective reuse from legacy repo only.

Event date from TOR: 5 January 2027 (main), 6 January 2027 (associated); abstract intake 1–20 December 2026, subject to editable official settings. Do not invent people, venues, fees, hour, results or acceptance.

## Agent read order

1. [WORKSPACE.md](WORKSPACE.md), [AGENTS.md](AGENTS.md), [19F Board](docs/19F-CURRENT-SPRINT-BOARD.md).
2. [20A contract](docs/20A-MASTER-PLAN-SKILLS-AND-HARNESS.md) and [19C Work Order](docs/19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) for the authorized scope.
3. Open only relevant Skills and 2–4 primary blueprint docs for the active milestone, following [19D Traceability](docs/19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md).

## Roadmap (not an authorization to execute)

| Milestone | Task references | Success evidence |
|---|---|---|
| M0 Agent workspace/readiness | PRE-01..04, 19A–20A | One plan + board; owner decisions and read-only local toolchain inspected; NO CODE |
| M1 Laravel foundation | S0-01..07, optional S0-08 later | Bootable project, auth/scoped RBAC, private file baseline, migrations on test DB, verified builds/tests |
| M2 Public frontend + Website CMS | S1-01..08 | Admin can create/edit/publish/preview/rollback pages/sections without editing source; truthful countdown; mobile/RTL QA |
| M3 Abstract intake | S2-01..06 | Draft + valid Text/PDF submit, own-paper security, direct reviewer index, 403/422/deadline tests |
| M4 Reviewer & academic decisions | S3 and 18B-03 | Reviewer recommends; Committee publishes ACCEPT; full-paper unlock regardless of payment |
| M5 Finance/Co-Host/package | S4 and 18B-04..08 | Bank mutation confirmation before Verified; one institutional IDR3m invoice/five sponsored papers; personal IDR1m/four activities |
| M6 Full Paper, sessions/revision | S5–S6, 18B-09 | OJS-like metadata, immutable versions, Presented and postpresentation revision/final decision |
| M7 Event operations | S7 | Scoped QR/attendance/fulfillment and certificates with audit and negative tests |
| M8 Publication handoff | S8 | Journal/outlet-specific staging export; selection is NOT journal acceptance |
| M9 Integrated UAT/release gate | S9 | E2E, security, mobile/RTL, backups and staging sign-off; separate production GO |

## Execution gates

- G0 Ready: owner-approved repo/DB/PK/auth/runtime decisions; verified local toolchain and safe test DB.
- G1 Authorized: explicit owner GO for the exact task ID **under current rules**; a sprint-level authorization is merely a proposed future model and does not apply yet.
- G2 Scoped: feature branch, in-scope diff only; no source changes on main, no secrets/data.
- G3 Verified: actual test commands and exit status; negative/security cases; screenshots for UI. Failed/unrun tests are not PASS.
- G4 Reviewed: ChatGPT reviews diff and evidence; owner decides acceptance/merge. Deployment separately authorized.

Stop on ambiguous decisions, tests failing, scope creep, production credentials or risky operations. **48-hour aspiration**: M1 and a limited demonstrable M2 if dependencies permit; M3 stretch/next wave. Never present M4–M9 as completed just to meet a timebox.

## Status and evidence ownership

**Only [19F](docs/19F-CURRENT-SPRINT-BOARD.md) is the live status board, updated by ChatGPT.** [harness/build-log.md](harness/build-log.md) is a future evidence journal (currently ChatGPT-owned). Codex cannot edit Markdown under the current code-only policy. No coding GO has been issued.

This plan becomes binding only when owner approves the governance proposal in Draft PR #1. Even after approval, plan existence does not authorize implementation.
