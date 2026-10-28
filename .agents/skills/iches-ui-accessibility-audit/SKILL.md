---
name: iches-ui-accessibility-audit
description: Read-only UI accessibility, interaction and responsive audit for ICHES Vue 3 Inertia Tailwind 4 public website and backoffice; uses a pinned snapshot of Vercel Web Interface Guidelines, without remotely fetching mutable instructions or adding React/Next dependencies.
metadata:
  project: iches-unisya
  origin: vercel-web-interface-guidelines-adaptation
  runtime: static-markdown-only
---
# ICHES UI Accessibility & Interaction Audit

Adapted for ICHES from [Vercel Web Interface Guidelines](https://github.com/vercel-labs/web-interface-guidelines/blob/434b7f91364665f2f733b310ec54809bf8f37937/command.md), MIT source license at `.agents/third-party-licenses/vercel-web-guidelines-MIT.txt`. Related upstream agent entrypoint: [vercel-labs/agent-skills](https://github.com/vercel-labs/agent-skills/tree/1a2c79d4e6c4a2283dc2c614b6538cbe1ed5ca62/skills/web-design-guidelines).

**Use the pinned local rule snapshot** [references/vercel-web-interface-guidelines.md](references/vercel-web-interface-guidelines.md). Do **NOT** fetch the mutable upstream `main` rules on every execution or install Vercel/React/Next.js tools; this is Laravel 13, Inertia, Vue 3 Composition API, TS and Tailwind 4 with shadcn-vue only when scoped.

## Read-only review procedure

1. Read current ICHES Work Order, four native Skills, route/role context and the local snapshot. Lock baseline, target file paths and review viewport/language.
2. Inspect actual template/component files: semantics (`button` for action, `a`/Inertia `Link` for navigation), accessible names, labels, focus and skip navigation, error messages/status, touch target, contrast and image alt/credits.
3. For Vue, use Vue event syntax and reactive lifecycle; do **not** apply React-specific snippets or import Next navigation. Avoid client-side URL assumptions in Inertia.
4. Exercise EN/ID/AR with `dir=rtl` in actual browser when authorized; desktop/tablet/mobile, reduced-motion, keyboard-only, disabled/loading/validation states. Browser not run = `NOT_TESTED`.
5. Produce findings grouped by severity with `file:line`, expected behavior, observed failure, smallest fix, and test evidence. Mark no-file access distinctly from PASS.
6. This Skill is **audit only**; Codex writes fixes solely under a named owner GO on feature branch. Never infer CMS/Finance privileges or approve automatic merge.

Accessibility quality is distinct from aesthetic approval and does not override frozen ICHES design or data/finance rules.
