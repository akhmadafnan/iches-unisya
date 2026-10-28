# 21I-04 — ICH-S0-07 Independent Review of Local CI Implementation

> **28 Oct 2026 · REVISIONS_REQUIRED (ONE RELEASE-BLOCKING ISSUE) · LOCAL FEATURE COMMIT UNPUSHED · GITHUB CI NOT RUN.** Static independent assessment of Codex VS Code deliverable, under the named `ICH-S0-07` GO and [21I-02 Work Order](21I-02-ICH-S0-07-CI-WORK-ORDER.md). This documentation is not source-code implementation, push/PR/merge permission, DB permission or deployment.

## Identifiers and actual evidence

| Check | Independent finding |
|---|---|
| Local canonical repository | `D:\PINJAM-AFNAN\Herd\iches-unisya`, canonical origin `akhmadafnan/iches-unisya` |
| Local branch | `feat/ich-s0-07-ci` |
| Exact commit | `17e335049f209110e76a03dc99bc588cc7cee925` |
| Parent / approved base | `42a8633245ad00a3eb809f03afded3f7d07955d5` |
| Local tree status | **CLEAN** |
| Scope | **PASS** — exactly 2 changed paths: `.github/workflows/ci.yml` added (73 lines); `harness/build-log.md` append-only (+33, -0) |
| Whitespace | `git diff --check origin/main...HEAD` exit 0 |
| Protected code/governance | **PASS** — no PHP/Vue/TS app files, DB schema, migrations, vendor dependencies, `AGENTS.md`, docs/Skills, secrets or lockfile changed |
| Upstream branch | `origin/feat/ich-s0-07-ci` **NOT PRESENT** when checked with GitHub API; no PR or GitHub Actions execution |
| Local tests | **REPORTED PASS BY CODEX**, not independently rerun in this audit. `composer validate --strict`, Laravel 13.39.0, 3 tests/22 assertions, Pint, npm ci, TS typecheck, Vite build and actionlint all exit 0 in the owner-provided actual handoff/log |
| Linux Node 22 | **NOT_RUN** — local Node 26.4.0 |
| GitHub CI | **NOT_RUN / PENDING_PUBLICATION** |

## Positive review of workflow

- `pull_request` against `main` and `push` to `feat/**`; grouped branch concurrency cancellation to minimize duplicate runs.
- `permissions: contents: read`; `actions/checkout` has `persist-credentials: false`.
- Ubuntu 24.04, PHP 8.4, Node 22, 15-minute timeout. Composer and npm read committed lockfiles with `composer install` and `npm ci`.
- Commands: `composer validate --strict`, `php artisan --version`, `php artisan test`, `php vendor/bin/pint --test`, `npm run typecheck`, `npm run build`.
- `DB_CONNECTION=disabled`, file session/cache, sync queue, array mail. No MySQL service, migrations, production token, deployment or real bank data.
- The `actions/checkout` `v6` SHA `d23441a48e516b6c34aea4fa41551a30e30af803` is a true tag commit. `actions/setup-node` `v6` SHA `249970729cb0ef3589644e2896645e5dc5ba9c38` is also a true tag commit.
- Composer's transient local Packagist DNS/cache warning was reported transparently; it is **not evidence that a clean Ubuntu runner will complete dependency download**. `actionlint` checked the workflow grammar/expression syntax; its ShellCheck and Pyflakes integrations were disabled in the reported command.

## Blocking issue REV-01 — wrong Git object type for setup-php SHA

**Severity: HIGH / MUST FIX before owner ACCEPT or publish approval.**

The file `.github/workflows/ci.yml` currently contains:

~~~yaml
uses: shivammathur/setup-php@d52fc211a0436f8839a587b3b5b8efddab4744ea # v2
~~~

Independent direct GitHub Git API verification:

- `refs/tags/v2` is an **annotated Git tag object** `d52fc211a0436f8839a587b3b5b8efddab4744ea`, with `type: tag`.
- That tag object dereferences to a **commit** `eb7c497e18156a6bbabfef1d3a82760b9eda3962`.
- GitHub's documented SHA-pinning guidance requires the **full commit SHA**. An annotated tag-object hash is not an equivalent verified commit pin; the action's resolution may fail in actual GitHub Actions. GitHub API commit lookup for `d52fc...` returned 422 (“No commit found”), while tag dereference returned a valid commit. No assertion is made about a specific live runner failure because CI has not run.

**Exact required correction:**

~~~yaml
uses: shivammathur/setup-php@eb7c497e18156a6bbabfef1d3a82760b9eda3962 # v2
~~~

GitHub guidance: [Finding/customizing actions — full-length commit SHA](https://docs.github.com/en/actions/how-tos/write-workflows/choose-what-workflows-do/find-and-customize-actions). The source action: [shivammathur/setup-php](https://github.com/shivammathur/setup-php).

## Bounded remedy, no new task GO needed

The owner already authorized implementation of `ICH-S0-07`; a minimal review correction **within that same bounded task** is permitted. **Only Codex VS Code** may edit the workflow and append an accurate correction entry to `harness/build-log.md`; ChatGPT must not edit application CI.

1. Keep local branch `feat/ich-s0-07-ci` and existing commit intact. Do **not** rebase, reset, amend, merge `origin/main`, switch to `main` or overwrite history to incorporate docs-only report updates.
2. Replace only the setup-php SHA above; keep all 72 other workflow lines and all other pinning, triggers and checks unchanged unless independent validation reveals an additional concrete CI-specific defect.
3. Append to `harness/build-log.md` a note that the earlier "three pins verified against upstream tags" assertion was incomplete for the annotated `v2` tag; record the tag object SHA and actual target commit, correction, tests and result. Do **not** rewrite historical evidence.
4. Run `actionlint -shellcheck= -pyflakes= .github/workflows/ci.yml`, `git diff --check` and relevant local CI checks with actual exit codes. Independently verify pin object type; report exact path diff and clean status.
5. Make a **new follow-up local commit** on the same branch; do not amend `17e335...`. **STOP before push or PR**; approval for publication/merge is still a separate owner decision.
6. ChatGPT re-audits the corrected local commit. Actual CI Linux/Node22 remains `NOT_RUN` until the branch is published and a real GitHub Actions run exists.

## Superseding post-fix checkpoint (28 October 2026)

REV-01 has been **CLOSED** by follow-up local commit `c420d95175d7d25cd9fee6719180acaf0b684903`; the corrected source action uses actual `v2` target commit `eb7c497e18156a6bbabfef1d3a82760b9eda3962`, and the review found a one-line-only workflow change plus append-only log. **Current verdict is REVIEW_PASS / OWNER_ACCEPTANCE_PENDING**, not the pre-fix `REVISIONS_REQUIRED` documented below. Actual GitHub CI remains **NOT_RUN**. Full evidence: [21I-05 final review](21I-05-ICH-S0-07-FINAL-REVIEW.md).

## Review decision

**REVISIONS_REQUIRED / REV-01 HIGH.** Structural/scope checks PASS; reported local checks PASS; reproducible pinned action is incorrect; CI runtime untested. This is one small actionable correction, not a full rewrite or separate feature task. After fix, review may advance to `REVIEW_PASS / OWNER_ACCEPTANCE_PENDING`. Never mark GitHub CI PASS before an actual workflow check.
