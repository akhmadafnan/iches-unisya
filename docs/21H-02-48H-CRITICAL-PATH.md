# 21H-02 — 48-Hour Critical Path, Scope and Authorization Gates

> **Owner's operational timebox: two working days.** This is an aggressive implementation target, **not a guarantee** that all academic/finance/CMS modules can be built, tested and released in 48h, nor an implicit GO CODING. Use canonical `akhmadafnan/iches-unisya` only; all source changes still exclusively by Codex VS Code under a named owner Work Order and feature branch.

## Current factual baseline

1. Laravel 13/Inertia/Vue3/TS/Tailwind4 `ICH-S0-01`: **owner-accepted, independently tested** on local branch `feat/ich-s0-01-foundation`, commit `8eba7cb5b9da0577910beb919844165869591e05`, Herd 200. **Not yet pushed or merged**.
2. Local Git HTTPS push blocked with `fatal: could not read Username for 'https://github.com'`; Git Credential Manager already installed, but per-repository credential helper missing. **Owner must complete OAuth/browser sign-in locally**, never share credentials/tokens in chat, logs or repo. Auth setup is separate from code GO.
3. 21G Skills W1 merged and simulated read-only Codex validation PASS, auto-trigger not proven. 21H docs Skills W2 are **separate, not yet merged**.
4. Test MySQL isolated database/login gate unresolved for schema task `ICH-S0-02`; no owner GO on DB changes. No claim of ready payment/OTP/finance/CMS.
5. Product and roles remain defined in the approved RD-01..06 decisions; full product features remain in backlog until individually authorized, implemented and verified.

## Delivery sequence / proposed time budget (not authorization)

| Period after confirmed GO and readiness | Concrete target & gate | Critical dependency |
|---|---|---|
| 0–4h | Get Git Credential Manager/browser auth working **in canonical local repo only**, Codex pushes accepted `ICH-S0-01` unchanged, ChatGPT creates feature PR, review and separately owner-authorized merge | Local authenticated Git, owner PR merge decision |
| 4–8h | Verify isolated disposable MySQL user/DB and safe migration gate, finalize 2-day *named* task scope and test seams | Owner local DB setup, no shared-production DB |
| 8–18h | Authorized core backend schema + role separation, event state, auth decisions; actual feature tests and negative authorization checks | Separate owner GO for S0-02/Auth task, DB test ready |
| 18–30h | Authorized public Academic Editorial + minimal editable CMS within realistic tested scope | Explicit frontend/CMS GO; no unapproved facts or payments |
| 30–42h | Authorized submission/reviewer and finance-safety vertical slices **only if prerequisites green** | Auth/RBAC, DB, reviewer and invoice state tests |
| 42–48h | Regression, mobile/RTL, security, end-to-end proof, honest scope and UAT report; **no forced production release** | Actual commands, audit evidence, owner accept/merge |

**Important:** Slots are planning envelopes, not promises or automatic authorization. If DB/auth is not ready, prioritize tested foundation + authentic editorial public presence and visibly report deferred protected features. Never fake acceptance/payment/reviewer or publish an incomplete financial flow.

## The one immediate bottleneck (not solved by more Skills)

**Git push authentication.** Owner-approved push exists from `21F`. Recommended safe local-only setup procedure after explicit owner configuration approval: configure Git Credential Manager as repo-local helper (`git config --local credential.helper manager`) and use interactive user browser OAuth for GitHub. Do not set remote URL with token, do not enable username/password credentials, never request the token in ChatGPT. After auth, Codex runs preflight and pushes exact accepted `8eba7cb...` branch, then a PR is made via ChatGPT's GitHub connector; **merge remains separately authorized**.

## Done means independently verified

For every task: correct Git branch, scoped diff, PHP tests, TypeScript/Vite build, browser desktop/mobile/Arabic RTL where relevant, auth/RBAC negative tests, no secret leakage, no DB writes outside verified isolated test DB, task-specific approval and controlled PR. Product completion means all originally required modules are implemented and UAT-pass; do not mark "project complete" solely because an MVP page is published.

**No more repetitive planning round-trip:** use existing 21B/19B scope. Identify and authorize *one executable next task* at a time, keep Codex writing code, ChatGPT handling repo/docs/review, owner making GO/merge decisions.
