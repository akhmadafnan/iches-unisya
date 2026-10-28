# ICHES UNISYA 2027 — Instructions for Coding Agents

This repository currently contains an **ICHES 2027 product blueprint**, not a finished Laravel application. Never claim an app, CMS, countdown, database, tests or deployments exist unless verified in the working tree and runtime.

## 21G — External Skills pilot approval and precedence (28 October 2026)

User approved selective ICHES skills curation. **Only a named owner coding GO permits changes to application source.** See [Skills routing](docs/21G-01-SKILLS-ROUTING-POLICY.md), [provenance/licenses](docs/21G-02-SKILLS-PROVENANCE-AND-LICENSES.md), and [activation tests](docs/21G-03-ACTIVATION-AND-WAVE-PLAN.md). Native ICHES Skills and owner decisions outrank external skills. W1 pilot copies: `ponytail`, `ponytail-review`, `ponytail-audit`, `diagnosing-bugs`, `tdd`. **Do not enable any external install scripts, agent proxy/cloud/hooks, production migrations, branch merge or automatic deployment.** Only task-appropriate read-only checks before separately authorized coding. Third-party `Verdict: Ship` is not an owner merge approval; an upstream persistent session mode does not override ICHES task boundaries. This 21G change is on a dedicated documentation branch and not yet active in `main`.


## 21H — Static Design Skills (proposal branch only; NO CODE GO)

When owner-authorized frontend work is opened, prefer native `iches-public-frontend` / `iches-backoffice-ui` and [21H design routing](docs/21H-01-DESIGN-SKILLS-AND-SECURITY.md). Candidate ICHES-specialist Skills on `docs/21h-design-skills`: `iches-editorial-art-direction`, `iches-design-critique`, `iches-ui-accessibility-audit`. They are static adaptations of pinned Taste, Anti-Slop and Vercel methods, **not** their executable package. Do not use until the dedicated PR is reviewed/merged. Never install scripts, scrape/download media or override approved brand. They do not change named GO, code ownership, Git push or merge rules.

## Active AI agent workspace (read first)

- Start at [WORKSPACE.md](WORKSPACE.md); [docs/19F-CURRENT-SPRINT-BOARD.md](docs/19F-CURRENT-SPRINT-BOARD.md) is the sole current-task status and [docs/19E-AGENT-WORKSPACE-RUNBOOK.md](docs/19E-AGENT-WORKSPACE-RUNBOOK.md) defines the short operational handoff. Do not treat the sprint timebox or backlog as GO CODING.
- Work only on explicitly approved **Task IDs**: individual Task GO, or a bounded Sprint GO that names finite ordered Task IDs; never pull in unlisted modules and never work on main. See `docs/20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md`.
- ChatGPT owns governance docs and current 19F board. Codex implements authorized code after named Task/Sprint GO and may append sanitized evidence to `harness/build-log.md` and `harness/evidence/**` only under that GO. No automatic merge/deploy.

## Read the project before changing code

1. `README.md` and `docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md` — latest owner decisions have precedence.
2. `docs/10-ANALISIS-TOR-DAN-REBASELINE-2027.md` — primary TOR facts, including **main event 5 January 2027**, associated day **6 January 2027**. **Official start hour remains unconfirmed**.
3. For any public website or Backoffice Website CMS task, **use the local skill**:
   `.agents/skills/iches-public-frontend/SKILL.md`
   and read its `references/` selectively.
4. For finance, Co-Host, Academic, Role, OJS or QR behavior, review the matching blueprint docs; do not use AICIS workflows to override ICHES decisions.
5. **Implementation readiness (28 Oct 2026)**: read `docs/18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md`, `docs/18C-05A-DRAFT-REUSE-AUDIT-AND-MIGRATION-PLAN.md` and `docs/18C-05B-DRAFT-MVP-SLICES-EXIT-GATES-AND-UAT.md`. The canonical target repository, Laravel 13/PHP 8.4, MySQL/InnoDB, UUIDv7, scoped Policies and passwordless participant OTP/Magic Link are **OWNER-APPROVED RD-01–RD-06** in Decision Register 09/20D. `ICH-S0-01` is a separate DB-independent foundation task; DB migrations remain gated under `ICH-S0-02`. Current Windows repo: `D:\PINJAM-AFNAN\Herd\iches-unisya`. Only port reviewed foundation code from `akhmadafnan/international-conference-platform/develop`. Old Finance verification must be redesigned to match real bank mutation evidence. **S0/S1/S2 first** after explicit GO.

## ChatGPT × Codex VS Code task ownership (28 Oct 2026)

- **Codex VS Code = code implementer**, only after owner GO CODING for a Task ID or GO SPRINT for finite named Task IDs. It may edit work-order code paths and **append-only** `harness/build-log.md` / sanitized `harness/evidence/**`; it **must not edit** `PLANS.md`, `WORKSPACE.md`, `README.md`, `docs/**`, `AGENTS.md`, `.agents/skills/**` or product policies.
- **ChatGPT = non-code planning, specification, documentation, code review and GitHub coordination**. ChatGPT does not patch source code directly. If review finds a code defect, issue a change request back to Codex. Owner authorizes merge, deploy and scope changes.
- **Workflow docs**: `docs/19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md` (protocol), `docs/19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md` (task IDs), `docs/19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md` (Codex work orders), `docs/19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md` (decision-to-code traceability).
- **Skills:** `iches-codex-engineering` always; `iches-public-frontend` for public/CMS/countdown; `iches-backoffice-ui` for Admin; `iches-code-verification` for test evidence. Read only relevant Skills.
- **Governance 20B has been approved, but no named Task GO or Sprint GO has been granted.** READY_FOR_CODEX and consent to documentation are not coding authorization. No code on main, secrets, live data, auto merge or deploy.

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
