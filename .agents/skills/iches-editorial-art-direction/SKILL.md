---
name: iches-editorial-art-direction
description: Art-direction and visual exploration for the ICHES 2027 public academic conference pages, after a named owner-authorized frontend task. Adapted from pinned Taste Skill; static references only, no script execution, image downloads or brand overrides. Use for public homepage, hero, information architecture, typographic hierarchy, authentic imagery and responsive editorial composition.
metadata:
  project: iches-unisya
  origin: taste-skill-method-adaptation
  runtime: static-markdown-only
---
# ICHES Editorial Art Direction — Taste-inspired static adaptation

**This is a project-authored adaptation, NOT the complete original Taste Skill v2 runtime.** Original work: [Leonxlnx/taste-skill](https://github.com/Leonxlnx/taste-skill/tree/12af471f627f866411b4bd785b19646d3672784c), MIT notice in `../../third-party-licenses/taste-skill-MIT.txt` (actual root: `.agents/third-party-licenses/taste-skill-MIT.txt`). Original full script/library bundle is **NOT installed**; do not invoke `scripts/draw.mjs`, `inspect.mjs`, `assets.mjs` or any remote `npx` installer on this project.

## Activation and source of truth

Use **only** for named/authorized ICHES public frontend visual tasks, alongside the controlling `iches-public-frontend` and `iches-codex-engineering` Skills and the current Work Order. Planning/review tasks may remain read-only. The owner-approved product decisions and ICHES native Skills outrank all reference advice.

**Locked brand:** ivory `#F7F6F1`, forest `#164734`, ink `#17211C`, gold `#B79A5B`; orange-to-gold H; Inter body and Sora display; EN/ID/AR with actual Arabic RTL QA; **no carousel**. This brand takes priority over Taste's proposed type/palette and over any randomized art direction.

## Task-bounded method

1. Read the active task/Work Order, conference truth and CMS content boundaries. Identify the visitor action: submit abstract, learn about event, register or review details. No presenter payment-before-ACCEPT CTA.
2. Write a one-sentence editorial thesis grounded in conference, place, academia and UNISYA. Choose a real information hierarchy: no invented speakers, awards, quotes, acceptance, partner logos, date/time or statistics.
3. Make one **layout direction sheet** (not generic options): desktop/mobile section order, intended rhythm, asymmetry/negative space, typography role map, imagery slots with verified rights and actual content, interaction states, RTL mirror plan.
4. Derive consistent design tokens from the locked brand; preserve foreground contrast, focus, keyboard and form semantics. No inventing decorative patterns merely to fill a section.
5. Produce an intentionally simple first pass. A public website must have a coherent story without animation. Respect `prefers-reduced-motion`.
6. Review actual rendered DOM, screenshots at desktop/tablet/phone and EN/ID/AR RTL **only when the Work Order authorizes code/browser checks**. Report exact tests and limitations, never claim a browser run from a markdown-only review.
7. Explain 3–5 specific compositional decisions; propose actionable deltas, not blind redesign.

## Available, pinned upstream references (READ ONLY)

- [Taste layout methods](references/taste-layout.md)
- [Taste critique methods](references/taste-critique.md)

References may include recommendations for tools, photo assets and dependencies that **are not installed or authorized here**. No automatic external image search/download or new dependency. Use only images the owner or project has rights to use, with source/credit recorded. A future approved, isolated prototype can test upstream Taste scripts after separate security review, not via this Skill's mere presence.

**Stop condition:** deliver reviewable art direction or, after explicit named GO, the scoped frontend code + screenshots/tests; no merge or deployment authority.
