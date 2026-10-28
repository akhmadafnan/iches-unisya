# 21I-10 — ICH-S0-07 Ubuntu GitHub Actions Verification & Owner Merge Gate

> **28 Oct 2026 · ICH-S0-07 CI_RUNTIME_PASS / REV-02 CLOSED / PR #5 OPEN_UNMERGED / OWNER_MERGE_APPROVAL_PENDING.** This document reports the actually observed corrected GitHub Actions job, not a synthetic or local-only result. The owner approved push and PR review, **not merge, database migration, another code task or deployment**.

## Verified scope and refs

- Canonical repository: [akhmadafnan/iches-unisya](https://github.com/akhmadafnan/iches-unisya).
- [PR #5](https://github.com/akhmadafnan/iches-unisya/pull/5) is **OPEN / Ready for Review / UNMERGED**.
- GitHub remote feature ref, PR head and owner-accepted corrected SHA all equal **`294a54b223676df3b6e9124480e271ee3a50bdc7`**.
- The three-dot PR diff against up-to-date `main` is **exactly three scoped paths**: `.github/workflows/ci.yml` (added **76** lines), `harness/build-log.md` (append-only **82** lines), `tests/Feature/FoundationSmokeTest.php` (**14** new test-only lines). There are **zero deletions** across all three. Feature commits: original CI `17e3350`, REV-01 correction `c420d951`, REV-02 correction `294a54b`. Protected governance, original app behavior, credentials, DB/migrations, CMS and deployment untouched.
- GitHub reports `mergeable=true` (no conflict), but **`mergeable_state=unstable`** despite the latest successful PR run; see cancellation nuance below. Do **not** equate `mergeable=true` with all repository required checks being satisfied.

## Actual workflow-run evidence (GitHub API, jobs and decoded logs)

| Run | Trigger | Head | Actual conclusion |
|---|---|---|---|
| [40678863079](https://github.com/akhmadafnan/iches-unisya/actions/runs/40678863079) | `pull_request` for PR #5 | `294a54b223676df3b6e9124480e271ee3a50bdc7` | **SUCCESS** (complete) |
| [40678855656](https://github.com/akhmadafnan/iches-unisya/actions/runs/40678855656) | `push` for same new SHA | `294a54b223676df3b6e9124480e271ee3a50bdc7` | **CANCELLED** by workflow's shared-ref concurrency policy; not a passing run |
| [40669157978](https://github.com/akhmadafnan/iches-unisya/actions/runs/40669157978) | `pull_request` on old `c420d951` | old SHA | Historical **FAILURE**, REV-02 pre-fix |
| [40668248799](https://github.com/akhmadafnan/iches-unisya/actions/runs/40668248799) | `push` on old `c420d951` | old SHA | Historical **FAILURE**, REV-02 pre-fix |

The success run's job [122784321633](https://github.com/akhmadafnan/iches-unisya/actions/runs/40678863079/job/122784321633) had every primary step completed with `conclusion=success`:

1. Checkout exact source; set up **PHP 8.4.27**, composer v2, and `composer validate --strict`.
2. **`composer install` from existing lockfile PASS** on clean Ubuntu, eliminating earlier concern that local cached dependencies hid a Packagist issue.
3. **Laravel 13.39.0**, temporary `cp .env.example .env` (runner-only, no production secret), `php artisan test`: **4 PASSED / 36 assertions** (log), including negative missing-Inertia-component assertion. **REV-02 Linux file-path fix proven**.
4. `php vendor/bin/pint --test`: **PASS, 18 files**.
5. `actions/setup-node` with **Node version requested 22**, `npm ci` from lockfile, `npm run typecheck`, `npm run build`: **all step conclusions SUCCESS**. Vite **7.3.7** production build completed in **2.04s**.
6. No database service, migration, secret use, CMS, real finance workflow or deployment step.

These are actual GitHub Actions results, not local Windows/Node26 assumptions. The real workflow did not report project-specific browser/RTL/UAT, which are outside S0-07.

## PR check nuance and decision

The new SHA's checks list contains **one completed SUCCESS** (PR job) and **one completed CANCELLED** (push job), not two successes. The workflow's `concurrency.group` deliberately collapses duplicate pushes/PR checks for the same branch. GitHub currently reports `mergeable=true` and `mergeable_state=unstable`, so before merge verify whether a branch-rule required-check policy expects a separate push status or treats cancellation as blocking. **Do not automatically rerun a cancelled duplicate or change code** without a concrete need. A complete successful required PR job is available for the exact current SHA.

**Technical verdict: REVIEW_PASS / CI_RUNTIME_PASS / REV-02 CLOSED.**

**Action decision: OWNER_MERGE_APPROVAL_PENDING.** Only if the owner explicitly authorizes **MERGE PR #5** may ChatGPT re-check exact head `294a54b223676df3b6e9124480e271ee3a50bdc7`, exact three-file diff, current checks and conflict state, then call the merge action with `expected_head_sha`. No CI result or previous publication permission grants merge authority. No database changes or deployment permitted.
