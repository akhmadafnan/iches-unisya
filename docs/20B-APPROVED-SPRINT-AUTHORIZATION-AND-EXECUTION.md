# 20B — Bounded Sprint Authorization & Evidence Contract

> **Governance workflow approved 28 October 2026; NO APPLICATION CODING GO.** Owner's "Oke saya setuju" authorizes preparing these workflow instructions and Skills. It does NOT authorize any specific sprint, source changes, deployment or merge.

## 1. Two valid authorization models

- **Task GO**: owner explicitly writes `GO CODING ICH-S0-01` referencing a ready work order. Only that Task ID is authorized.
- **Sprint GO**: owner explicitly writes `GO SPRINT <ID>` referencing a ready Sprint Work Order with **finite ordered Task IDs**. Only those tasks may be implemented. Codex can continue to the next named task after relevant tests pass and evidence is logged, without requesting GO for each subtask.

The presence of this file, a Draft PR, "setuju", or "lanjut" is **not** an implementation GO. A named Sprint Work Order needs: exact Task IDs/order, approved technical decisions and prerequisites, branch/PR strategy, allowed code paths, excluded work, test environment, exit gates, stop conditions and owner authorization reference. No wildcard like "all tasks" or "M0–M9".

## 2. Write permissions — limited exception

On an owner-approved code task/sprint and **on a feature branch only**, Codex may:
1. Edit only authorized app code/tests/dependency/CI paths.
2. **Append-only** chronological test/progress evidence to `harness/build-log.md`. Existing entries must not be deleted or rewritten.
3. Add sanitized evidence to `harness/evidence/**` where useful, no private or sensitive data.

Codex must **never modify** `AGENTS.md`, `PLANS.md`, `WORKSPACE.md`, `README.md`, `docs/**`, `.agents/skills/**`, decision register or 19F Board. ChatGPT owns those. Codex log is evidence, **not status approval**; 19F is the only current status board.

## 3. Task execution and hard stops

1. Verify Git remote, branch, HEAD and worktree; inspect latest Work Order, relevant Skills and 2–4 primary blueprints.
2. Execute **one atomic named task**; test against isolated local/test environment.
3. Run actual automated tests and relevant negative/security tests, record command/exit/evidence. PASS is required for promotion to next named task.
4. If failed or blocked, repair in-scope issue; if it cannot be resolved safely, **STOP** and report. Do not falsify PASS to meet the two-day aspiration.
5. ChatGPT reviews diff and updates 19F; owner approves acceptance, merge, release or deployment separately.

**STOP:** unknown DB/PK/auth decisions, wrong repo/branch, dirty unrelated changes, out-of-scope task, unreviewed legacy payment verification, real bank transactions/credentials, production migrations, breaking/destructive Git changes, missing test evidence or unsolved permission flaws.

## 4. Required Sprint Work Order template

```text
SPRINT ID:
STATUS: DRAFT / READY / GO (GO only after owner message)
ORDERED TASK IDS (finite list):
REPOSITORY / BASE COMMIT:
BRANCH / PR STRATEGY:
APPROVED DECISIONS / DEPENDENCIES:
SCOPE IN / OUT:
ALLOWED CODE PATHS:
ALLOWED EVIDENCE: harness/build-log.md append; harness/evidence/** sanitized
PRIMARY DOCS / RELEVANT SKILLS:
TEST COMMANDS / NEGATIVE CASES:
MILESTONE GATES / STOP CONDITIONS:
HANDOFF: actual diff, command outputs, screenshots, blockers
NO AUTO MERGE / DEPLOY / PRODUCTION ACTIONS
```

## 5. Initial Skills registry

| Skill | Trigger | State |
|---|---|---|
| `iches-codex-engineering` | Every authorized code task | Existing |
| `iches-public-frontend` | Public frontend/CMS/countdown | Existing |
| `iches-backoffice-ui` | Admin, dashboard, tables/forms, RTL | Added in 20B |
| `iches-code-verification` | Actual tests, security/QA, PR handoff | Added in 20B |

Do not load all Skills on every task. Admin uses **shadcn-vue + Tailwind CSS 4 + Vue/Inertia**; public site uses original Academic Editorial Tailwind/Vue; **no Bootstrap**. Lyra visual preset is a reference pending exact Vue-compatible token verification. No React/Next.js preset installer.

**21B first-task exception to Sprint-01 DB gate:** owner may explicitly authorize **one Task `ICH-S0-01`** after governance PR merge and [21B Work Order](21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md) marked READY. No database, auth, migrations or subsequent task authorization is implied. The three-task `ICH-SPRINT-01` stays blocked for authenticated test DB.

## 6. Still blocked before first GO

- [x] Draft PR #1 reviewed and owner-approved, merged to `main` (governance merge SHA `275cfbfd0e431033eb05d2c519eda19282465962`), local `main` synced clean.
- [x] Owner approved RD-01–RD-06 in 20D / Decision Register 09, including passwordless participant login.
- [x] Codex Skills readable, PHP/Composer/Node/Git/Herd verified locally.
- [ ] Isolated authenticated test DB remains a gate for schema/migrations `ICH-S0-02`, **not** for DB-free `ICH-S0-01`.
- [ ] Actual named Sprint Work Order prepared and marked READY.
- [ ] Owner issues **separate explicit GO SPRINT** or GO CODING for a Task ID.

**48-hour development target is a timebox, not a guarantee of completing S0–S9.**
