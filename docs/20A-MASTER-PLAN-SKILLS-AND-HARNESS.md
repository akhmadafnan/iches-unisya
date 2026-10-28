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
| [harness/build-log.md](../harness/build-log.md) | Chronological actual execution/test evidence, NOT approval or progress authority | ChatGPT today; restricted Codex append permission is PROPOSED |
| [AGENTS.md](../AGENTS.md) + [19C](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) | Agent permanent rules + individual authorized work orders | ChatGPT + owner |

Existing 18B/18C documents remain source-of-truth references. Codex reads at most 2–4 relevant references per task, not all blueprint files.

## 2. Authorization — keep CURRENT rule until owner changes it

**CURRENT ENFORCED:** GO CODING must explicitly name each Task ID. Codex edits **code only** in a feature branch. No README/docs/AGENTS/Skills or other Markdown edits by Codex. No automatic merge, deploy or live database actions.

**FUTURE PROPOSAL — Sprint Authorization (NOT ACTIVE):** after owner explicitly approves and governance files are updated, one GO may cover a **finite, named list of Task IDs** for a fixed scope. It must specify branch strategy, allowed paths, test environment, success gates, maximum tasks, stop conditions, and whether evidence logs may be updated. Never means unrestricted M0–M9 execution. Decisions beyond approved scope, production operations, and merge remain separate approvals.

**Important:** A draft master plan or this document gives Codex NO new permissions. Owner approval of the design and a subsequent explicit GO for code are different.

## 3. Execution protocol (after GO)

1. Read Git branch/HEAD/status; verify target repo. Read AGENTS, PLANS overview, 19F status, active work order, relevant Skills and source docs.
2. State goal, scope IN/OUT, touched paths and tests before editing. Stop on unresolved decisions.
3. Implement one atomic vertical slice + related tests; do not copy old payment verification or administrative screening.
4. Run test/build/typecheck and negative authorization cases against test resources; collect exact commands and exit codes.
5. Report Task ID, branch/PR/commit, diff, actual tests, screenshot when UI, failures, unresolved risks and proposed next step.
6. ChatGPT reviews and updates 19F. Owner accepts/authorizes merge. Only then begin another **authorized** task.

**Journal exception (proposed only):** Codex may later append to `harness/build-log.md` and place redacted test evidence in `harness/evidence/` if owner approves exact paths AND ChatGPT updates AGENTS/19A/engineering Skill. It may NEVER modify PLANS, 19F, product policies or Skills. Before approval, Codex returns evidence in message; ChatGPT writes Markdown.

## 4. Skills registry — minimal, precise triggers

| Skill | Status | Activation trigger | Non-goals |
|---|---|---|---|
| `iches-codex-engineering` | EXISTS | Every authorized code task | No policy edits/scope expansion |
| `iches-public-frontend` | EXISTS | Public web, CMS, countdown, SEO, localization | No imaginary content or static-only fake CMS |
| `iches-backoffice-ui` | CANDIDATE NOT CREATED | Role-scoped Admin screens, sidebar, tables, forms and accessibility | No Bootstrap/React; no frontend permission enforcement alone |
| `iches-code-verification` | CANDIDATE NOT CREATED | Reusable regression, PHP tests, Vue build/typecheck, browser & RTL QA | No PASS without actual run |

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
- [ ] Owner review Draft PR #1 and decide whether to change authorization model.
- [ ] If approved: ChatGPT reconciles 19A/19C/AGENTS/engineering Skill before enabling new authorization or Markdown log writes.
- [ ] Local Codex/VS Code/toolchain verified; no coding until separate GO.
