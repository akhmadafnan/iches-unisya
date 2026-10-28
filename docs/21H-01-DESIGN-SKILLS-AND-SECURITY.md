# 21H-01 — ICHES Design Skills Integration & Security Contract

> **28 October 2026 · DESIGN SKILLS WAVE 2 PROPOSED IN DOCS-ONLY PR · NO NEW GO CODING.** Canonical repository: [akhmadafnan/iches-unisya](https://github.com/akhmadafnan/iches-unisya). No separate product repository or project clone is created. This branch changes only `docs/**`, `AGENTS.md`, `WORKSPACE.md`, `.agents/**`.

## Goal and choices

Deliver three project-scoped, safe, static-methodology Skills in the **same repository**, without installing upstream runtimes:

| New ICHES Skill | Upstream used | Function | Default mode |
|---|---|---|---|
| `iches-editorial-art-direction` | [Taste Skill v2](https://github.com/Leonxlnx/taste-skill/tree/12af471f627f866411b4bd785b19646d3672784c) | Public Academic Editorial composition, approved palette/type and image story | Plan/review, no code without GO |
| `iches-design-critique` | [Anti-Slop Design](https://github.com/Ferousco-dev/anti-slop-design/tree/484f99edef59020e564cf28fdf77aaca27b32c3f) | Visual/copy/interaction quality audit after art direction | Read-only |
| `iches-ui-accessibility-audit` | [Vercel Web Interface Guidelines](https://github.com/vercel-labs/web-interface-guidelines/blob/434b7f91364665f2f733b310ec54809bf8f37937/command.md) | Vue/RTL/mobile/focus/forms/semantic audit | Read-only |

These are **adaptations, not exact vendored copies of the original SKILL.md files**. Pinned Taste and Vercel reference docs and full MIT license notices are unchanged copies. The Anti-Slop checklist preserves upstream content except an explanatory provenance line and four rewrites of out-of-bundle relative links to exact pinned upstream URLs. Relevant rules are specifically adapted to native ICHES policies and Vue; no claim that Taste's automated draw/inspect mode is installed.

### Rationale against full upstream install

- Taste library is ~1.5 MB JSON plus Node+Chrome/CDP scripts; `assets.mjs` can contact external image services and download media. Avoid auto network, temporary `.taste-skill` files, unlicensed photos and runtime drift until explicitly security-reviewed and worth testing.
- Anti-Slop full package contains many generalized style prohibitions. Use its pinned checklist rather than importing every rule with an aggressive always-on trigger; ICHES already freezes brand and conference product truth.
- Vercel entrypoint fetches mutable remote design rules per invocation; here a local pinned exact rules snapshot prevents silent policy changes. No React/Next.js/Vercel deployment assumptions.

### Precedence & routing

1. Explicit Owner Task GO and decision register → `AGENTS.md` → active Work Order → four native ICHES Skills.
2. For frontend direction: **`iches-public-frontend` + `iches-editorial-art-direction`**.
3. For separate visual review: **`iches-design-critique`**; for UI/a11y/interaction review: **`iches-ui-accessibility-audit`**. Invoke only relevant specialist per phase; native verification Skill owns real test evidence.
4. All audit Skills are read-only by default; no role can self-authorize frontend code, DB migration, CMS, push/merge or deployment.

**Non-negotiable:** ivory `#F7F6F1`, forest `#164734`, near-black `#17211C`, gold `#B79A5B`; orange→gold H; Inter/Sora, EN/ID/AR RTL, no carousel, authentic licensed media. Presenter payment only after ACCEPT, participant passwordless, official date/time only. No fake site facts, and no unauthorised automation or dependencies.

### Verification gates

- G21H-01 exact reference/LICENCE match to pinned source commit SHA; 3 Skills have valid distinct names and relative links.
- G21H-02 diff docs/`.agents` only, no `composer.json`, npm, PHP, Vue or application content.
- G21H-03 Codex explicitly reads 3 Skills and maps scenarios; tests owner-go/brand/RTL/no-fake-facts negative cases **read-only**.
- G21H-04 actual automatic activation **NOT_TESTED until independently observed**; do not falsely mark PASS.
- G21H-05 PR merge requires **explicit owner approval**; not included in "lanjut" or the 48h ambition.

Upstream license notices retained: `.agents/third-party-licenses/{taste-skill,anti-slop-design,vercel-web-guidelines}-MIT.txt`. Updated SHA needs source diff + separate reviewed docs PR. No original API token or installer is involved.
