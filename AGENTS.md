# ICHES UNISYA 2027 — Instructions for Coding Agents

This repository currently contains an **ICHES 2027 product blueprint**, not a finished Laravel application. Never claim an app, CMS, countdown, database, tests or deployments exist unless verified in the working tree and runtime.

## Read the project before changing code

1. `README.md` and `docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md` — latest owner decisions have precedence.
2. `docs/10-ANALISIS-TOR-DAN-REBASELINE-2027.md` — primary TOR facts, including **main event 5 January 2027**, associated day **6 January 2027**. **Official start hour remains unconfirmed**.
3. For any public website or Backoffice Website CMS task, **use the local skill**:
   `.agents/skills/iches-public-frontend/SKILL.md`
   and read its `references/` selectively.
4. For finance, Co-Host, Academic, Role, OJS or QR behavior, review the matching blueprint docs; do not use AICIS workflows to override ICHES decisions.
5. **Implementation readiness (28 Oct 2026)**: read `docs/18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md`, `docs/18C-05A-DRAFT-REUSE-AUDIT-AND-MIGRATION-PLAN.md` and `docs/18C-05B-DRAFT-MVP-SLICES-EXIT-GATES-AND-UAT.md`. The canonical target repository and MySQL/UUIDv7 are **recommended, not owner-approved decisions**. Only port reviewed foundation code from `akhmadafnan/international-conference-platform/develop`. Old Finance verification must be redesigned to match real bank mutation evidence. **S0/S1/S2 first** after explicit GO.

## Non-negotiable product guidance

- **Academic Editorial** is the chosen *visual direction*; **individual frontend components/layouts are not locked**. The owner expects AI to propose cohesive design, not request approval for each section. Accept visual revisions.
- **Countdown to main conference is mandatory** and must be editable via Admin; use correct timezone-aware master time, never invent a start hour.
- All public website content should be managed from **Backoffice → Website CMS** (pages, flexible sections, media, menus/footer, EN/ID/AR, SEO, preview/publish/revisions) without routine code changes.
- shadcn-vue Backoffice, original public design; Laravel 13 + Inertia + Vue 3 + TS + Tailwind 4 + i18n EN/ID/AR RTL, no carousel.
- No ICHES post-submission administrative screening for Abstract Text+PDF. Published ACCEPT unlocks Full Paper regardless of payment.
- Co-Host one institutional IDR3m invoice/5 sponsored papers after Finance Verified, no double conference fee; personal Package IDR1m unlocks four entitlements after Verified.
- Bank Settings Superadmin-only, Finance verification after matching bank mutation. Publication selection is not journal acceptance.
- Never fabricate speakers, logos, dates, fees, stats, venues, journal partners, or completed UAT.

## Work authorization

**Do not begin full Laravel/Vue implementation solely because this instruction file or Agent Skill exists.** Proceed with coding only after owner explicitly gives GO. Documentation and design planning can be improved independently when requested. For authorized implementation, deliver coherent desktop/mobile previews and actual test results; list pending issues honestly.

## Developer entry points

- `docs/18C-04E-AICIS-FEATURES-COUNTDOWN-CMS-AND-AGENT-SKILL.md` — latest public feature agreement.
- `docs/18C-04B-DRAFT-PUBLIC-FRONTEND-ACADEMIC-EDITORIAL-CMS.md` — visual direction.
- `docs/18C-04D-DRAFT-VISUAL-CMS-BLOCK-LIBRARY-EDITING-CONTRACT.md` — flexible CMS.
- `docs/18C-02-DRAFT-LARAVEL-BACKEND-ARCHITECTURE-BUSINESS-LOGIC.md` — backend architecture.
