---
name: iches-design-critique
description: Read-only anti-slop visual and content quality audit for ICHES website or backoffice screens. Uses a pinned Anti-Slop Design checklist, adapted to preserve fixed UNISYA brand and academic product constraints. Does not implement redesigns unless separate owner GO.
metadata:
  project: iches-unisya
  origin: anti-slop-design-checklist-adaptation
  runtime: static-markdown-only
---
# ICHES Anti-Slop Design Critique

Adapted for ICHES from [Ferousco-dev/anti-slop-design](https://github.com/Ferousco-dev/anti-slop-design/tree/484f99edef59020e564cf28fdf77aaca27b32c3f), MIT license retained at `.agents/third-party-licenses/anti-slop-design-MIT.txt`. Original anti-slop product rules **do not supersede** the owner-approved brand or four ICHES-native Skills. This Skill is deliberately a **read-only critique role**, not a second competing art director.

## When to invoke

When reviewing public conference frontend or CMS/Backoffice visuals, after design direction is defined. For initial visual direction, first use `iches-editorial-art-direction`; for product constraints, `iches-public-frontend` or `iches-backoffice-ui`. Do not add unrequested features, alter website data, run scripts or write source without a named GO.

## Audit in order

1. **Truth:** no invented academic partners, claims, speakers, participant counts, paper outcomes, testimonials, venues, payment decisions or fake countdown time.
2. **Hierarchy:** meaningful focal point, section order suited to visitor jobs, varied rhythm and enough negative space; avoid template hero, repetitive feature cards, purely decorative labels and generic “Elevate/Unlock/Seamlessly” copy.
3. **Brand:** keep ivory/forest/ink/gold, H accent, Inter/Sora and authentic ICHES identity. Use Arabic-capable fallback for RTL. “Avoid generic Inter headline” must **not** eliminate owner's Inter body/Sora display decision.
4. **Function:** clear CTA after appropriate workflow stage; accessible links/buttons/forms, focus states, error/success/empty states, contrast, keyboard and reduced motion.
5. **Responsiveness:** 390px mobile plus tablet/desktop, correct RTL direction and genuine Vue + Inertia interactions; no horizontal overflow. Check from actual render when authorized, otherwise mark NOT_TESTED.
6. **Quality verdict:** concrete findings with file/path/element and severity CRITICAL/HIGH/MEDIUM/LOW; include smallest suggested fix and whether it blocks the named task's acceptance.

Read pinned upstream [pre-emit checklist](references/anti-slop-checklist.md) as a **heuristic**, not a universal prohibition. No forced redesign when the owner locked a style. **Never** claim “approved to merge” because an aesthetic checklist passed.

Outputs: `PASS / REVISIONS_REQUIRED / NOT_TESTED` with evidence, and explicit exclusions; **no code writes by default**.
