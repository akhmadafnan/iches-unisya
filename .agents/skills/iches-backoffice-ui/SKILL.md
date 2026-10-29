---
name: iches-backoffice-ui
description: Implement and review owner-authorized ICHES Backoffice admin screens with shadcn-vue, Vue 3, Inertia and Tailwind 4. Use for dashboards, sidebar, breadcrumb, data tables, forms, dialogs, CMS editor, role-scoped menus, responsive and Arabic RTL admin behavior; not for public-site layout.
---
# ICHES Backoffice UI

**Not a GO to code.** Read `AGENTS.md` and the engineering Skill. Implement only named authorized Task IDs/sprints. This Skill handles **Admin/Backoffice**, not the public Academic Editorial website.

## Sources
- `docs/18C-04-DRAFT-FRONTEND-DASHBOARD-ARCHITECTURE-UX.md` and `docs/18C-04A-DRAFT-SCREEN-INVENTORY-COMPONENTS-INTERACTION-UAT.md`.
- `docs/18A-ROLE-PERMISSION-DAN-NAVIGASI-BACKOFFICE.md` for scoped navigation and workspace identity.
- If Website CMS is in scope: also read the `iches-public-frontend` Skill and relevant CMS contract.
- Check `docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md` for owner decisions; avoid loading all blueprints.

## Design contract
- Laravel + Inertia + Vue 3 + TypeScript, **shadcn-vue Admin components and Tailwind CSS 4**. **No Bootstrap; no React/Next.js runtime or template install.**
- **OWNER FINAL (29 Oct 2026):** Backoffice/Admin uses official **shadcn-vue Lyra V1.1 preset `a33J5lFw`**, not merely Lyra-inspired: Reka + Lyra (`reka-lyra`), neutral/emerald theme, JetBrains Mono body/Geist Mono headings, Phosphor icons, default radius, subtle/default menu. Public/Auth remain **Academic Editorial ICHES** with existing Inter/Sora, ivory/forest/near-black/gold; keep EN/ID/AR RTL. Do not execute React/Next preset or blindly overwrite existing Vue components.
- **Historical pre-audit direction (superseded):** Preferred visual direction: Lyra V1-inspired compact Admin, but preset compatibility and final tokens require verification. Do not execute an unverified React preset command.
- Coherent reusable sidebar, breadcrumb, searchable/filterable tables, semantic forms, dialogs and status components. Keep accessible empty, loading, error, forbidden and validation states.
- Mobile/tablet/desktop responsive; keyboard navigation, focus trapping, contrast and `dir=rtl` Arabic support. Test RTL portals, calendar, pagination and sidebar.
- **Visible UI permissions are not authorization**: server Policies/Gates must reject cross-event, institution, role and owner access.
- No fabricated metrics, payments, papers, speakers, venue or inactive buttons disguised as working features.

## Workflow after named GO
1. Inspect installed frontend code and component registry; reuse existing local components. Do not assume shadcn-vue is already installed.
2. Define the screen contract and scoped route/action mapping; edit only work-order paths.
3. Add working UI and relevant tests; check keyboard, mobile, RTL and negative authorization cases.
4. Run actual typecheck/build and browser QA when available; report PASS/FAIL/BLOCKED truthfully.
5. Append concise, sanitized evidence to `harness/build-log.md` **only with explicit Task/Sprint GO**. Do not alter docs, Skills, PLANS or 19F board.

**Exit:** real backend integration, responsive usable Admin, no Bootstrap/React dependency, actual verified tests and traceable diff.

[20B governance](../../../docs/20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md)
