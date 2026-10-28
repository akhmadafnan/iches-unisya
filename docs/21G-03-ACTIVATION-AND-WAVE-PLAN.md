# 21G-03 — Skill Activation Tests, Security Gates & Further Waves

> **PILOT PENDING MERGE / NO CODE GO.** This is a verification plan for five vendored, commit-pinned W1 Skills. Do not call installation or Codex auto-discovery PASS until actually tested on the user's chosen device and agent.

## Post-merge checkpoint (28 October 2026)

- PR #2 was explicitly authorized and merged, SHA `e5362f919fbd7ce23fdd50761b5dc99fa9410e18`.
- Separate detached QA worktree for a real read-only Codex test is available at `D:\PINJAM-AFNAN\ICHES-SKILLS-READONLY`. Five third-party Skill YAML names verified there; nine total Skill folders (four native + five external). Original Laravel feature remains untouched.
- See [21G-05 actual Codex handoff](21G-05-POST-MERGE-CODEX-READONLY-TEST.md). Codex invocation and automatic activation remain **NOT_TESTED**, requiring actual response from VS Code. Do not conflate terminal file-read PASS with Codex activation PASS.


## 21G Wave 1 acceptance gates

| Gate | Evidence required | Initial state |
|---|---|---|
| G21G-01 Source provenance | All 5 W1 `SKILL.md` exact-match their pinned upstream, with 3 additional reference/script files exact-match | **PASS / GitHub content audit** |
| G21G-02 Licensing | Both full MIT license notices exact-match original source files | **PASS / GitHub content audit** |
| G21G-03 No app impact | 17 changed paths, docs/Skills/license only, zero application code; local accepted feature `8eba7cb5...` clean and unchanged | **PASS / GitHub + local read-only** |
| G21G-04 Agent can read | Codex VS Code identifies **all five** `.agents/skills/*/SKILL.md` after user-authorized documentation merge and safe local sync | **NOT RUN** |
| G21G-05 Correct activation | In read-only dry run, choose `ponytail-review` for PR review; `ponytail-audit` for full repo; `diagnosing-bugs` for bug; `tdd` for preapproved task test seams; `ponytail` for scope-lean authorized implementation | **NOT RUN** |
| G21G-06 Negative tests | No named GO → no source change; `Verdict: Ship` not merge; TDD no unauthorized database; no password/auth scope leak; no script auto-run | **NOT RUN** |
| G21G-07 Responsive/no-duplication | Third-party Skills do not prevent native Skills from triggering or override protected brand/governance | **NOT RUN** |
| G21G-08 Owner merge decision | Draft PR reviewed and owner explicitly approves merge | **PENDING** |

### Read-only Codex dry-run prompt, AFTER owner-approved merge and safe checkout

~~~text
READ-ONLY SKILLS DRY RUN — ICHES 21G. Do NOT edit files, git push, change branch, create DB, run migrations, execute shell helpers, or install dependencies. Verify repo origin, branch and clean state, then read AGENTS.md, docs/21G-01-SKILLS-ROUTING-POLICY.md and .agents/skills/{ponytail,ponytail-review,ponytail-audit,diagnosing-bugs,tdd}/SKILL.md. Report which of five are readable; map: (a) narrow reviewed PR → ponytail-review, (b) whole repo audit → ponytail-audit, (c) diagnosed bug → diagnosing-bugs, (d) preapproved test seam → tdd, (e) authorized minimal implementation → ponytail. Show one negative example: a request to merge a PR without owner approval must STOP. Do not claim auto-triggering tested unless actual auto-trigger is exercised. Return only PASS/FAIL/NOT_TESTED, evidence and conflicts.
~~~

### Wave 2 — Frontend (do not copy yet)

- **Taste Skill v2 `design-taste-frontend`**: assess its Node/Chrome scripts, `.taste-skill/` output paths, unreviewed image downloads and license. Disable untrusted media auto-fetch. Use `--keep palette,type` and owner approved real photography. First run on a disposable non-product visual prototype after Task GO.
- **Anti-Slop**: add as *design review checklist*, not competing art director. Filter anti-pattern rules against the intentionally fixed Inter/Sora and ICHES brand.
- **Vercel `web-design-guidelines`**: read-only UI/a11y checklist only. Source references a remotely fetched set of current rules; audit its exact rules and pin a tested revision before enabling automatic checks. React/Next/Vercel deploy rules are out of scope.

### Wave 3 — Specialized (do not copy yet)

- **iart** `accessible-animation`, `60fps-animation`: prefer platform CSS + reduced motion, no Framer Motion/Next.js dependency. Test only when event UI actually needs movement.
- **Anthropic** `skill-creator` and `webapp-testing`: use as reference when improving native skills or browser QA, verify directory-specific Apache 2.0 licenses and Python/Playwright tool needs. Existing Node browser QA is preferred if sufficient.
- **Caveman**: *no full runtime/proxy installation*. Evaluate tiny `investigate-first` / `verify-and-stop` only if not redundant with W1 and native Skills.
- **Matt Pocock `code-review`**: strong two-axis review, but assumes issue tracker and parallel subagents; adapt only after checking actual Codex support and avoid self-authorized ticket/merge operations. Other Skills are future candidates.

## Recovery

If selected Skills cause confusion/overhead, roll back only the 21G documentation PR or remove the problematic copied Skill on a dedicated documentation fix PR. **Never reset/rebase the accepted ICH-S0-01 code branch** to manage agent instruction changes.
