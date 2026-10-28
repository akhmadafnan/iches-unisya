# 20A — Master Plan, Skills Registry & Execution Harness

> DRAFT FOR OWNER REVIEW · 28 Oct 2026 · DOCUMENTATION ONLY · NO GO CODING. Adds operational detail to 19A–19F, not new business policies.

## 20B status update

Workflow for finite Sprint GO and Codex append-only execution evidence has been accepted **as governance**, not as an implementation GO. Two new task-focused Skills now exist on Draft PR #1. See [20B](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md). If draft suggestions below conflict, 20B governs after merge.

## 1. One plan, no duplicate statuses

| Artifact | Single responsibility | Maintainer |
|---|---|---|
| [PLANS.md](../PLANS.md) | Permanent M0–M9 roadmap, dependency gates, common quality criteria | ChatGPT + owner |
| [19B](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) | Granular task IDs / detailed acceptance; no new duplicate task ledger | ChatGPT |
| [19F](19F-CURRENT-SPRINT-BOARD.md) | **Only** current task status and one next action | ChatGPT |
| [harness/build-log.md](../harness/build-log.md) | Chronological actual test evidence, NOT acceptance or a status board | Codex append-only **after named Task/Sprint GO**; ChatGPT owns governance |
| [AGENTS.md](../AGENTS.md) + [19C](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) | Agent permanent rules + individual authorized work orders | ChatGPT + owner |

Existing 18B/18C documents remain source-of-truth references. Codex reads at most 2–4 relevant references per task, not all blueprint files.

## 2. Authorization — keep CURRENT rule until owner changes it

**CURRENT ENFORCED AFTER 20B:** Explicit GO CODING may name one Task ID **or** GO SPRINT may refer to one finite approved ordered task list. Codex edits code only in a feature branch, plus narrow append-only execution evidence exception. No governance Markdown edits, auto merge, deploy or live database actions.

**APPROVED WORKFLOW (NO ACTUAL SPRINT GO):** one explicit owner GO SPRINT referring to a complete approved Sprint Work Order may cover a **finite, named list of Task IDs** for a fixed scope. It must specify branch strategy, allowed paths, test environment, success gates, maximum tasks, stop conditions, and whether evidence logs may be updated. Never means unrestricted M0–M9 execution. Decisions beyond approved scope, production operations, and merge remain separate approvals.

**Important:** A draft master plan or this document gives Codex NO new permissions. Owner approval of the design and a subsequent explicit GO for code are different.

## 3. Execution protocol (after GO)

1. Read Git branch/HEAD/status; verify target repo. Read AGENTS, PLANS overview, 19F status, active work order, relevant Skills and source docs.
2. State goal, scope IN/OUT, touched paths and tests before editing. Stop on unresolved decisions.
3. Implement one atomic vertical slice + related tests; do not copy old payment verification or administrative screening.
4. Run test/build/typecheck and negative authorization cases against test resources; collect exact commands and exit codes.
5. Report Task ID, branch/PR/commit, diff, actual tests, screenshot when UI, failures, unresolved risks and proposed next step.
6. ChatGPT reviews and updates 19F. Owner accepts/authorizes merge. Only then begin another **authorized** task.

**Journal exception (20B approved; requires named GO):** Codex may append to `harness/build-log.md` and place redacted test evidence in `harness/evidence/` only on an authorized feature branch after Task/Sprint GO. It may NEVER modify PLANS, 19F, product policies or Skills. Before named GO, no Codex log writes; ChatGPT owns Markdown documentation and board.

## 4. Skills registry — minimal, precise triggers

| Skill | Status | Activation trigger | Non-goals |
|---|---|---|---|
| `iches-codex-engineering` | EXISTS | Every authorized code task | No policy edits/scope expansion |
| `iches-public-frontend` | EXISTS | Public web, CMS, countdown, SEO, localization | No imaginary content or static-only fake CMS |
| `iches-backoffice-ui` | ADDED IN 20B | Role-scoped Admin screens, sidebar, tables, forms and accessibility | No Bootstrap/React; no frontend permission enforcement alone |
| `iches-code-verification` | ADDED IN 20B | Reusable regression, PHP tests, Vue build/typecheck, browser & RTL QA | No PASS without actual run |

Do **not** create Finance, Academic, OJS, QR, or extra Skills just because the domain exists: create after a repeated reusable workflow with clear triggers, required inputs, outputs and verification criteria is identified. Local folder existence does not prove VS Code Skills auto-discovery; check PRE-03.

**Visual baseline:** shadcn-vue for Backoffice and original Tailwind 4 + Vue for Public; no Bootstrap. Lyra V1 was mentioned in discussion, but verify exact preset and Vue compatibility before locking tokens; React/Next.js preset reference in docs/07 is *visual only*, never executable in Vue.

## 5. Quality gates and 48h cut line

- READY = current decisions + local environment verified + owner authorization + task-specific test plan.
- DONE = code/diff within scope + actual command/results + relevant negative/security proof + ChatGPT review + owner acceptance. **Merge and deploy remain separate.**
- STOP = dirty unrelated worktree, unresolved DB/auth/business rules, financial verification without actual bank mutation, secrets/real data, failed tests, unauthorized file modifications.
- 48-hour target begins only when tooling and first GO are ready. Prioritize Foundation then Public/CMS/countdown if dependencies allow; Abstract may follow. Other modules must NOT be marked complete without evidence.

## 6. Acceptance checklist for this 20A document phase

- [x] One master roadmap draft and milestone exit criteria documented.
- [x] Explicit split between roadmap, detailed backlog, live board and execution log.
- [x] Proposed bounded sprint GO vs currently enforced per-task GO distinguished.
- [x] Skill inventory and triggers documented; no automatic generation of extra Skills.
- [x] Harness evidence format documented; no permission for Codex to edit yet.
- [x] Owner approved bounded Sprint Authorization and log exception as governance; **no named GO**.
- [ ] Owner reviews/merges Draft PR #1.
- [x] ChatGPT reconciled 19A/19C/AGENTS/engineering Skill on Draft PR branch; applies to Codex only after checkout/merge and actual named GO.
- [ ] Local Codex/VS Code/toolchain verified; no coding until separate GO.
