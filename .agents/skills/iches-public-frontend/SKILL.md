---
name: iches-public-frontend
description: Design, build, refine, or audit the ICHES UNISYA 2027 public conference website and its Backoffice Website CMS. Use for homepage, hero, countdown, Call for Papers, conference timeline, programs, speakers, partners, journal/proceedings information, SEO, EN/ID/AR RTL, responsive UI, and reusable editorial components. Respect ICHES product rules and produce clean, non-generic academic editorial experiences.
metadata:
  project: iches-unisya
  status: proposed-skill-for-future-ai-agent
---

# ICHES Public Frontend & CMS — Agent Skill

## Your mission

Create a premium, accessible, responsive **Academic Editorial** public website for **The 6th International Conference on Humanity, Education, and Society (ICHES) 2027**, with a capable **Website CMS inside its Laravel Admin Backoffice**. The owner wants AI assistance to implement the website from this skill, **without lengthy one-by-one approvals of individual components**. Take thoughtful design initiative; provide coherent previews and accept revisions. **No hero, layout, section order, component collection, photo, or navigation style is frozen.**

**Before coding**, inspect repository status, read its README and the decision register, then confirm that the user has authorized implementation. The presence of this skill in a documentation-only repository is **not GO to code**. Do not describe a mockup as implemented.

## Source-of-truth reading order

1. Repository `README.md`, `docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md`, and `docs/10-ANALISIS-TOR-DAN-REBASELINE-2027.md`.
2. Public design & CMS: `docs/18C-04B-DRAFT-PUBLIC-FRONTEND-ACADEMIC-EDITORIAL-CMS.md`, `docs/18C-04C-DRAFT-HOMEPAGE-WIREFRAME-DESKTOP-MOBILE.md`, `docs/18C-04D-DRAFT-VISUAL-CMS-BLOCK-LIBRARY-EDITING-CONTRACT.md`, `docs/18C-04E-AICIS-FEATURES-COUNTDOWN-CMS-AND-AGENT-SKILL.md`.
3. **New implementation readiness gate**: `docs/18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md`, `docs/18C-05A-DRAFT-REUSE-AUDIT-AND-MIGRATION-PLAN.md`, `docs/18C-05B-DRAFT-MVP-SLICES-EXIT-GATES-AND-UAT.md`. Public/CMS/countdown is a **first build slice**, not a last-minute decoration. The repo currently has no Laravel source; do not claim it is implemented.
4. Auth, role/security & integration: `docs/18C-03B-DRAFT-SWITCH-ROLE-WORKSPACE-AND-USER.md`, `docs/18C-04-DRAFT-FRONTEND-DASHBOARD-ARCHITECTURE-UX.md`, `docs/18C-02-DRAFT-LARAVEL-BACKEND-ARCHITECTURE-BUSINESS-LOGIC.md`.
4. On-demand references here: [DESIGN-DIRECTION](references/DESIGN-DIRECTION.md), [CMS-AND-DATA-CONTRACT](references/CMS-AND-DATA-CONTRACT.md), [QUALITY-GATES](references/QUALITY-GATES.md).

Resolve conflicts using the **latest explicit owner decisions in the register**. Prior wireframes are **examples only**. Do not silently replace them with invented business rules.

## Product guardrails (do not break)

- Conference main day **5 January 2027**; related activities **6 January 2027**, according to TOR. **Exact start time for the countdown is not yet approved**; read an Admin-configurable `countdown_target_at` with `Asia/Jakarta` timezone/offset. Do not assume midnight or create a fictional date/hour.
- **Countdown is a required public feature**, inspired by AICIS: days/hours/minutes/seconds, actual one-second ticking, clamped at zero and transitioned to event-in-progress/concluded state. Admin can edit the target, label, visibility, translations and presentation variant; default target is the authoritative start of the main event once time is set. Require functional tests.
- AICIS (https://aicis.uinssc.ac.id/) is **feature/information-architecture inspiration**, NOT a UI to clone or a source of ICHES rules. Relevant public features: hero/theme, countdown, event essentials, CFP/submission info, tracks, timeline, programs, speakers, partners, downloads, FAQ, optional truthful statistics.
- ICHES **Abstract Submit requires text + PDF** and valid submissions go directly to Reviewer Abstract Index; NO AICIS-style administrative screening stage. Reviewer self-claim and Academic Committee final ACCEPT remain authoritative.
- Published final ABSTRACT ACCEPT unlocks Full Paper **even if presenter invoice unpaid**. Full Paper metadata follows an OJS-like editorial input; post-Presented assessor can recommend no/minor/major revision, Committee decides revision/Final ACC; publication/export claims must be accurate.
- Co-Host **IDR 3,000,000/institution** on one invoice, 5 representative seats/5 sponsored Conference papers after Finance Verified, **no second presenter Conference invoice** on ACCEPT. Personal International Program Package **IDR 1,000,000/user** unlocks exactly four activities after Verified. Finance Verified occurs only after real manual bank-mutation checking. Never copy hardcoded prices into CMS blocks as source-of-truth.
- Proceedings versus selected journal are separate routes. Do not claim journal acceptance or OJS import compatibility absent evidence.
- Public website content may be maintained by Admin/CMS; no CMS permission automatically grants Finance, bank edit, Academic ACCEPT, Presented, Final ACC, or certificate issuance.
- Backoffice remains shadcn-vue-based; public frontend should **not look like a dashboard template or AI-generated boilerplate**. One coherent Laravel 13 + Inertia + Vue 3 + TypeScript + Tailwind 4 + shadcn-vue system, no unnecessary microservice or second CMS.

## Visual direction: Academic Editorial, never AI slop

- Brand: ivory `#F7F6F1`, forest `#164734`, dark `#17211C`, gold `#B79A5B`; established ICHES orange-to-gold H accent. Inter for body, Sora for editorial display; use tested Arabic-capable fallback for `ar`.
- Strong information hierarchy, intentional negative space, responsive editorial grids, restrained treatments, authentic approved photography, differentiated sections. **No carousel**.
- Do not fabricate speakers, logos, attendee stats, testimonials, venue photographs, confirmations, awards, dates, OJS acceptance, publication partners or journal names.
- Avoid generic gradients/glassmorphism, identical cards repeated in every section, excessive shadows, random illustrations, gratuitous animations, unlabeled icons, overdecorated hero, fake “live” counters.
- Components **remain editable**: choose the best initial composition autonomously, but do not treat any hero or 12-section catalog as locked. Distinguish theme/brand decisions from revisable implementations.

## Build workflow when implementation is authorized

1. **Inspect** repository tree, existing code (if any), documented TOR facts, backend routes, existing CMS/storage and user decisions. If no app yet, propose a minimal coherent build slice; do not claim framework or testing already present.
2. **Design a cohesive homepage + inner-page system**. Use AICIS for feature coverage, adapt original content hierarchy; first-pass layouts for desktop **and** mobile. Include countdown. Do not demand the owner decide each component individually. Make a well-reasoned default, show reviewable result, iterate on feedback.
3. **Implement typed, reusable blocks and layouts** with a controlled yet extensible registry. Every visible public section must be CMS-addressable: text, media, links, visibility, ordering and supported layout variants. Admin can create pages and add multiple sections, not merely edit a fixed homepage. Component additions require extending the registry, not replacing the whole site. Details in CMS reference.
4. **Implement Backoffice → Website CMS** with Pages, Sections/Components, Navigation/Footer, Media Library, EN/ID/AR translations, SEO, Preview, Publish/Revisions/Rollback and Countdown Settings. The UI is admin-friendly and uses shadcn-vue.
5. **Use authoritative bindings** to Conference/Event/Programs/Publications for date, registration windows, prices, tracks, access state and partner publication status; never duplicate a competing business truth in free text.
6. **Build countdown correctly** using event-local ISO8601 offset and server-provided time. Test pre-event, zero boundary, after-start and time changes from CMS. Do not hardcode a JS Date literal from a wireframe.
7. **Deliver production-quality accessibility/performance**: semantic hierarchy, focus/keyboard, mobile/desktop + Arabic RTL, image optimization/alt/credit, fast hydration, SSR/SEO if deployment supports, reduced-motion, responsive interactions.
8. **Run real checks**: lint/typecheck/build, component and browser smoke tests, multiple viewports and languages, permission/preview restrictions, countdown fake clock, CMS draft-versus-published, no fake content. Capture screenshots where tools permit and compare to quality gates. Report actual tests, failures and compromises honestly.
9. **Explain the outcome** with exact paths/commit when working in repo, working vs pending capabilities, and a concise review invitation. Owner can request any compositional revision.

## Deliverable acceptance

A good outcome is **not** “many sections on a page.” It is a public site that looks intentionally art-directed, communicates conference essentials, correctly counts down to the Admin-selected event time, and can be updated via an effective Backoffice CMS without code changes for routine content and layout variants. Provide a coherent initial implementation/design **without one-by-one component interrogation**. If the design deviates from owner preference, **revise** rather than assert finality.

**Never mark implementation, CMS, countdown, translations or UAT as completed merely because a blueprint/skill exists.**
