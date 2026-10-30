# ICH-S1-04 REV-02 — countdown binding correction

30 October 2026, Asia/Jakarta. Explicit owner GO CODING REV-02 authorizes exactly two source/test paths, preserved REV-01 diagnostics, this evidence and one local child commit. No database or frontend verification authorized.

## Preflight and scope

Verified feature branch `feat/ich-s1-04-public-section-rendering`, HEAD `ae6d3f2314170146a80df84653874b01607b227f`, direct parent/local main/existing origin-main `11e90cf6343bfc776534a4c9795b8764353f7ad3`, official origin `https://github.com/akhmadafnan/iches-unisya.git`. Zero staged/tracked changes; exactly the three approved untracked REV-01 evidence files. No fetch, branch switch, sync, reset, rebase, amend or other Git history manipulation.

Complete correction-commit inventory (six paths):

```text
app/Services/Cms/PageSnapshotValidator.php
tests/Feature/CmsSnapshotContractTest.php
harness/evidence/ICH-S1-04/REV-01/diagnose.php
harness/evidence/ICH-S1-04/REV-01/diagnostic-results.json
harness/evidence/ICH-S1-04/REV-01/diagnostic-report.md
harness/evidence/ICH-S1-04/REV-02/verification.md
```

Only the first two existing tracked files were modified. No build-log change; original S1-04 evidence retained. Final SHA/parent/clean-tree and staged whitespace results are reported after staging/commit in the owner handoff.

## Root cause and minimal correction

The countdown-specific strict array identity comparison required object members in `source, field` order. Equivalent JSON objects may have members in `field, source` order. REV-01 established the validator defect without MySQL; its diagnostic script/output/report are preserved as historical before-fix evidence, not rerun expecting old rejection after the correction.

Production diff replaces only that comparison with three strict conditions: binding count exactly one, `source === 'event'`, and `field === 'starts_at'`. Prior list validation guarantees index 0; prior exact-key checks guarantee both members. No loose comparison, normalization, exception suppression or coercion. Catalog/schema, URLs, translations, media, IDs/positions, authorization and publication/draft rules remain unchanged. No writer, lifecycle, reader, model, persistence test or route change.

## Before/after regression evidence

Tests were added before changing production. Focused snapshot suite against the old validator exited **1**: **2 failed, 137 passed, 298 assertions**. The failures were exactly reversed-order valid bindings through array and JSON inputs. The source-first cases and invalid binding cases passed.

After the production fix, the same suite exits **0**: **139 passed, 304 assertions**. New coverage adds four valid data cases (two key orders multiplied by array/JSON input) and 52 invalid cases (26 malformed shapes/values multiplied by array/JSON input). Valid cases assert strict equality of the entire snapshot plus exact source/field strings, proving content/key order is preserved rather than silently rewritten. Invalid cases retain rejection of absent/multiple bindings, wrong source/field, missing keys, extra value/event ID, numeric/boolean/null/array values, positional/scalar/null binding, unwrapped/non-list/sparse/object lists and null/string list values. The existing no-fallback-hour countdown test and every other prior test are unchanged.

## Actual verification

Every PHPUnit process explicitly sets five boolean opt-ins to `0`: `ICHES_DB_TESTS`, `ICHES_CMS_DB_TESTS`, `ICHES_CMS_LIFECYCLE_DB_TESTS`, `ICHES_CMS_UI_DB_TESTS`, `ICHES_CMS_PUBLIC_RENDER_DB_TESTS`; `ICHES_CMS_DB_SCHEMA` is unset process-locally. phpunit.xml continues to force disabled DB. No .env, test configuration or global environment edit. No tests/Database file was executed by PHPUnit.

| Command | Final exit | Result |
| --- | --- | --- |
| `php artisan test --compact --no-ansi tests/Feature/CmsSnapshotContractTest.php` | 0 | 139 passed / 304 assertions |
| `php artisan test --compact --no-ansi tests/Feature/PublicCmsRenderingTest.php tests/Unit/PublishedSectionPresenterTest.php` | 0 | 27 passed / 384 assertions |
| `php artisan test --compact --no-ansi` | 0 | 197 passed / 1698 assertions; default Feature suite |
| `php -l app/Services/Cms/PageSnapshotValidator.php` | 0 | No syntax errors |
| `php -l tests/Feature/CmsSnapshotContractTest.php` | 0 | No syntax errors |
| `php vendor/bin/pint --test app/Services/Cms/PageSnapshotValidator.php tests/Feature/CmsSnapshotContractTest.php` | 0 | Passed |
| `git diff --check` | 0 | Passed |

Initial scoped Pint --test exited **1** for formatting. The authorized two-path Pint formatting run exited **0**, then scoped --test exited **0**. Complete source diff review shows no unrelated formatting changes: only the countdown expression and added test methods/providers remain. All PHP suites/lints/Pint were rerun successfully on the final formatted files. No security expected outcome was weakened.

## REV-01 preservation

SHA-256 values were recorded before edits and checked afterward; all match byte-for-byte:

| File under REV-01 | SHA-256 |
| --- | --- |
| `diagnose.php` | `E2EA15D7515E8A33165BB85EC1D999CD067A2FA3812819220265808CC0C12C22` |
| `diagnostic-report.md` | `A6AA9D60AEAF15097C76536A12634FFE5C1E6827D9BB739B51E7EF6F8AB8CF62` |
| `diagnostic-results.json` | `ED336BC42E0CFA973C3293CBDC91207F2BBCE5DA1E4F7F4E1278DFFDEEE9A029` |

## Limits and remaining gates

MySQL persistence tests NOT RUN; migrations/seeding/schema/data/reset/cleanup operations NOT RUN. No database modification or connection performed by this correction. TypeScript/build/browser checks NOT RUN, as excluded from this PHP-only verification scope. No push/PR/merge/deployment or next-task work.

The confirmed defect is fixed and covered DB-free. Whether MySQL key normalization explains all three prior persistence failures remains a hypothesis until separately approved persistence verification succeeds. Do not claim the MySQL suite now passes.

Next: independent ChatGPT source review, separate GO TEST ICH-S1-04 REV-02, then separate GO PUSH & PR only after verified MySQL PASS, followed by CI review and GO MERGE. Exactly one new local commit with parent `ae6d3f2314170146a80df84653874b01607b227f`; final identity/status in owner handoff.
