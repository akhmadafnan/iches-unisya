# 21I-07 — PR #5 GitHub Actions Failure Review & REV-02

> **28 October 2026 · PR #5 OPEN / OWNER_ACCEPTED LOCAL COMMIT / LIVE CI FAILED / REV-02 REVISIONS_REQUIRED / NO MERGE.** This is a post-publication engineering diagnosis, **not a new coding task GO, commit, deploy or merge authorization**.

## Grounded GitHub facts

- [PR #5 — CI DB-free PHP 8.4 and Node 22](https://github.com/akhmadafnan/iches-unisya/pull/5) opened after owner-approved push. Head remains `c420d95175d7d25cd9fee6719180acaf0b684903`, files **only** `.github/workflows/ci.yml` (+73) and append-only `harness/build-log.md` (+54); merge conflict-free but checks failing, GitHub `mergeable_state=unstable`. **Not merged**.
- [Push run 40668248799](https://github.com/akhmadafnan/iches-unisya/actions/runs/40668248799) **FAILED**, job `122748172334`.
- [PR run 40669157978](https://github.com/akhmadafnan/iches-unisya/actions/runs/40669157978) **FAILED**, job `122751236997`.
- Both GitHub runs used Ubuntu 24.04 / PHP 8.4 and successfully ran: checkout, PHP setup, Composer manifest validation, locked Composer install, Laravel version. Both **failed at `php artisan test`**, before Pint / Node22 / npm ci / TypeScript / Vite; those downstream steps are **SKIPPED / NOT_TESTED**, not PASS.
- PHPUnit actual output: **1 failed, 2 warnings (20 assertions)**. Hard failure `Inertia page component file [Foundation] does not exist.` in `tests/Feature/FoundationSmokeTest.php` at the `->component('Foundation')` assertion.
- Independent review checked actual tracked `resources/js/pages/Foundation.vue` (lowercase `pages`), and `resources/js/app.ts` resolves from `./pages/**/*.vue`. The installed Inertia Laravel package config `vendor/inertiajs/inertia-laravel/config/inertia.php` defaults **`inertia.testing.page_paths` to `resource_path('js/Pages')`** (uppercase `Pages`). This resolves on case-insensitive Windows, **fails on case-sensitive Linux**. The Inertia assertion must remain enabled; do not set `inertia.testing.ensure_pages_exist=false` or `component(..., false)` merely to pass CI.
- Two additional truncated `file_get_contents` warnings occurred prior to frontend `npm ci/build`; likely asset manifest lookup before build. Inspect their actual cause; address minimally/test-only, don't hide warnings blindly.
- A top-level [PR review diagnosis comment](https://github.com/akhmadafnan/iches-unisya/pull/5) records the failure and proposed bounded remedy.

## Post-correction local review checkpoint (28 October 2026)

The Codex REV-02 correction is now present as separate local commit `294a54b223676df3b6e9124480e271ee3a50bdc7`, and its code/tests were independently checked. **Local REV-02 verdict is REVIEW_PASS**, while the **old remote PR #5 GitHub CI remains FAILED** until this corrected commit is owner-authorized, pushed, and tested on Linux. See [21I-08](21I-08-S0-07-REV02-FINAL-LOCAL-REVIEW.md). This checkpoint supersedes historical local `REVISIONS_REQUIRED` below without erasing real CI failure history.

## Safe remediation path — same ICH-S0-07 task

The existing owner GO authorizes S0-07 CI-specific testing fixes under [21I-02](21I-02-ICH-S0-07-CI-WORK-ORDER.md), **not a new unrelated coding task**. Only Codex VS Code writes source, and only after preflight on the same feature branch.

1. Codex preserves existing commits `17e3350` and `c420d951`, does not rebase/merge/amend/reset to follow advancing docs-only main; confirms clean branch HEAD.
2. Apply the smallest test-only alignment of **`inertia.testing.page_paths`** to `resource_path('js/pages')` (for example in the `FoundationSmokeTest` setup) while retaining `component('Foundation')` file-existence verification. Ensure an actually missing page still fails.
3. Diagnose and eliminate Vite manifest warnings without changing application business logic; a scoped `withoutVite()` setup or another proven minimal strategy is appropriate if it preserves test intent. Only change CI step order if necessary and verified; avoid dependency/DB changes.
4. Re-run local PHP/Pint/Composer/TypeScript/Vite/actionlint as relevant, with exit codes; append accurate evidence to `harness/build-log.md` (no rewrite of old evidence); commit a separate follow-up on `feat/ich-s0-07-ci`. No extra feature work.
5. **Push publication permission was for exact old SHA `c420d951...`.** A new correction commit needs the owner's **separate approval before its push**. No force-push, no PR merge or deployment.
6. After new owner-approved push, recheck actual GitHub Actions **both PHP and Node 22 stages**, and request separate owner merge approval only after evidence PASS.

**Current status: REV-02 / REVISIONS_REQUIRED (CI real failure).** Earlier 21I-05 `REVIEW_PASS` was a correct **local-only** decision and is not evidence of a Linux CI pass. Do not auto-merge.
