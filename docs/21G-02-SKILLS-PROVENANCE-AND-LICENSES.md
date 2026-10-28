# 21G-02 — Third-Party Skills Provenance & License Register

> **28 October 2026 · WAVE 1 PILOT ONLY · NOT YET MERGED INTO MAIN / NOT YET VERIFIED IN CODEX.** All source copies below were fetched from exact source commit SHAs and must be byte-for-byte equivalent in UTF-8 content; no install script or shell helper was executed.

## Wave 1 — vendored Skills (pinned, unchanged text)

| Installed path in 21G branch | Source repository and original path | Pinned commit | License |
|---|---|---|---|
| `.agents/skills/ponytail/SKILL.md` | [DietrichGebert/ponytail](https://github.com/DietrichGebert/ponytail/blob/09c434ab73ca43761c5d8b0c5d341926c2fba710/skills/ponytail/SKILL.md) | `09c434ab73ca43761c5d8b0c5d341926c2fba710` | MIT |
| `.agents/skills/ponytail-review/SKILL.md` | [Ponytail Review](https://github.com/DietrichGebert/ponytail/blob/09c434ab73ca43761c5d8b0c5d341926c2fba710/skills/ponytail-review/SKILL.md) | same | MIT |
| `.agents/skills/ponytail-audit/SKILL.md` | [Ponytail Audit](https://github.com/DietrichGebert/ponytail/blob/09c434ab73ca43761c5d8b0c5d341926c2fba710/skills/ponytail-audit/SKILL.md) | same | MIT |
| `.agents/skills/diagnosing-bugs/SKILL.md` | [mattpocock/skills — diagnosing-bugs](https://github.com/mattpocock/skills/blob/59db609eb1e18aaf59b63284d0e52255aff0c63c/skills/engineering/diagnosing-bugs/SKILL.md) | `59db609eb1e18aaf59b63284d0e52255aff0c63c` | MIT |
| `.agents/skills/tdd/SKILL.md` | [mattpocock/skills — tdd](https://github.com/mattpocock/skills/blob/59db609eb1e18aaf59b63284d0e52255aff0c63c/skills/engineering/tdd/SKILL.md) | same | MIT |

**Bundled reference files copied from the same pinned commit:** `.agents/skills/tdd/tests.md`, `.agents/skills/tdd/mocking.md` and `.agents/skills/diagnosing-bugs/scripts/hitl-loop.template.sh`.

**License notices preserved:** `.agents/third-party-licenses/ponytail-MIT.txt` and `.agents/third-party-licenses/mattpocock-MIT.txt`. Respect MIT copyright and license notices in any future copies or modifications. These five third-party Skills do not replace any original ICHES-authored Skills.

### Safety and portability constraints

- **No installer or remote update** was run. The bundled Bash `hitl-loop.template.sh` is upstream example reference only: never run it automatically; it assumes `localhost:3000` and could solicit user input. For Windows Herd, use an authorized task-specific PowerShell/browser test instead. Do not allow it to print credentials or unredacted HAR/logs.
- `diagnosing-bugs` may recommend instrumentation/fixes and `tdd` may propose tests; **their presence grants NO code-write, DB, network, or environment permissions**. All edits require a separately named ICHES owner GO and scoped Work Order. A Work Order's preapproved test seams count as agreement; do not re-interview owner about seams already explicitly locked.
- `ponytail` has a session-persistence convention upstream. For ICHES, **scope it only to an explicitly authorized coding task**; no global persistent mode, and never sacrifice security, tests, payments, audit trails, accessibility or review gates for fewer lines.
- `ponytail-review` and `ponytail-audit` are **read-only review/report** modes. A `Verdict: Ship.` line is **NOT approval to merge/deploy**.
- No auto-install, auto-update, execution of scripts, proxy, cloud service, privileged hook, shell hook or CI workflow is enabled by these copied files.

## Eight-source discovery ledger (reviewed; NOT all installed)

| Upstream | Frozen source commit | Integration decision |
|---|---|---|
| [anti-slop-design](https://github.com/Ferousco-dev/anti-slop-design) | `484f99edef59020e564cf28fdf77aaca27b32c3f` | W2: curated design review; keep ICHES colors/type as authority |
| [caveman](https://github.com/JuliusBrussee/caveman) | `9b68f7ed12b05e7efaff18e65c5a3fc2849fd0a4` | No full proxy/CLI; optional tiny `investigate-first`, `verify-and-stop` after overlap test |
| [mattpocock/skills](https://github.com/mattpocock/skills) | `59db609eb1e18aaf59b63284d0e52255aff0c63c` | W1 2 Skills; W2 review `code-review` subagent/tracker assumptions before use |
| [taste-skill](https://github.com/Leonxlnx/taste-skill) | `12af471f627f866411b4bd785b19646d3672784c` | W2 art direction; Node/Chrome/browser scripts and asset rights need separate audit |
| [anthropics/skills](https://github.com/anthropics/skills) | `dbd4588f9e1033efb41dad4bef2f7947c8993d44` | W3 `skill-creator` / `webapp-testing` references, license per directory |
| [web-animation-skills](https://github.com/iart-ai/web-animation-skills) | `b6dba3eb759726845a44163ff0bad70dd9e7fbb6` | W3 `accessible-animation`, `60fps-animation`; avoid React-only recipes |
| [ponytail](https://github.com/DietrichGebert/ponytail) | `09c434ab73ca43761c5d8b0c5d341926c2fba710` | W1 three exact read-only/methodology Skills, scoped usage |
| [vercel-labs/agent-skills](https://github.com/vercel-labs/agent-skills) | `1a2c79d4e6c4a2283dc2c614b6538cbe1ed5ca62` | W2 `web-design-guidelines` only; skip React/Vercel deploy Skills for Laravel/Vue |

**Update policy:** manual source diff/review + source SHA change record + license audit + tests + separately approved PR. Never automatically track `main` of a third-party repository. Source authors' benchmarks and performance statistics are **not** independent proof in ICHES.
