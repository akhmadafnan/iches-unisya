# ICH-S1-04 REV-01 — stopped at production-source scope boundary

30 October 2026, Asia/Jakarta. Owner explicitly authorized diagnosis and at most one persistence-test-file correction. No MySQL execution was authorized. Preflight PASS: clean `feat/ich-s1-04-public-section-rendering`, HEAD `ae6d3f2314170146a80df84653874b01607b227f`, parent `11e90cf6343bfc776534a4c9795b8764353f7ad3`, official origin `https://github.com/akhmadafnan/iches-unisya.git`. No branch switch, sync, reset, rebase, amend or network Git operation.

## Decision

No test correction and no correction commit. A production validator defect is reproduced without MySQL. Altering the test to avoid countdown or to accommodate storage-dependent object-key ordering would conceal that defect. REV-01 section 5 explicitly requires stopping if production source needs a change. Only these diagnostic evidence files were created; production and test source, original S1-04 evidence and build log remain unchanged.

## Proven boundary and reproducer

`PageSnapshotValidator.php:68` uses strict PHP array identity for the entire countdown binding:

```php
$section['bindings'] === [['source' => 'event', 'field' => 'starts_at']]
```

PHP strict array identity also requires associative key order. Changing only the object-member order to `['field' => 'starts_at', 'source' => 'event']` reproduces the exact reported `Illuminate\Validation\ValidationException` message. Keys, values, types, section sequence, UUIDs, translations and all seven section variants remain intact. The caught stack identifies `require` at validator line 68. Both direct validator and writer revalidation reject it. The same reordered binding passes in an event-essentials section; this isolates the additional countdown comparison.

`diagnose.php` is a deterministic DB-free reproducer. It does not load/execute any tests/Database file or save models. It requires all five boolean DB opt-ins to equal `0`, schema opt-in unset/empty, and configures an unavailable `disabled` connection before any probes. The process boots Laravel for validation services only. Two runs exit 0, each confirming 22 diagnostic assertions, including the expected validation failures; this exit means the diagnosis reproduced successfully, not that the defect is fixed. Full output: `diagnostic-results.json`.

## Stages A–F

| Stage | Finding |
| --- | --- |
| A — construction | Original fixture is not shown invalid by the supplied evidence/source. All seven sections are present. |
| B — preparation | Exact by-reference media-clearing loop passes validation for all four supplied titles. No harmful reference mutation reproduced. No fixture content removed as a workaround. |
| C — writer | `validateSnapshot` passes for all four prepared no-media fixtures before persistence. Actual `saveDraft`/SQL not executed. |
| D — cast/hydration | Ordinary assignment to snapshot, JSON encoding by the real Eloquent array cast, then `newFromBuilder` hydration passes. The cast preserves key order, including a deliberately reordered binding. No MySQL round-trip is claimed. |
| E — lifecycle | Publish/restore retrieve a revision and call `PageRevisionWriter::validateSnapshot`, which reaches the proven order-sensitive comparison. Therefore reordered hydrated countdown data is rejected here. Actual location of the prior MySQL failures is not established from the abbreviated exception evidence. |
| F — reader | Cold-cache editorial validation reaches the same validator; the reader catches ValidationException and returns 404. It cannot repair publication failure, and was not changed. |

The previous DB-free inspection passed because neither PHP JSON serialization nor the ordinary Eloquent cast reorders these object members. It did not test equivalent bindings in another member order. Existing working persistence references generally use empty sections or no countdown, so their PASS does not cover this comparison.

## Connection to MySQL: supported inference, not a new database observation

The official [MySQL 9.7 JSON normalization documentation](https://dev.mysql.com/doc/refman/9.7/en/json.html#json-normalization) states that MySQL sorts JSON object keys and does not guarantee stable ordering across releases. This makes the production comparison unsuitable for persisted JSON. It supports object-member reordering during persistence as a strong explanation for the three reported failures, but this authorization did not observe the prior retrieved JSON or full failing stacks. Do not label the exact MySQL failure stage confirmed. No MySQL query, connection, persistence test, migration, seeder or schema/data operation was performed.

## Required scope for a real fix

Production path requiring separate authorization: `app/Services/Cms/PageSnapshotValidator.php`. Countdown must still require exactly one binding, exact allowed keys, source strictly `event` and field strictly `starts_at`, while accepting either JSON object-member order. All URL/text/media/locale/schema/type/count/position protections must remain. Add a focused regression at the existing validator test seam (`tests/Feature/CmsSnapshotContractTest.php`) covering both key orders and invalid/extra/missing bindings under a separately bounded work order. These files were not edited. The persistence tests should retain their current seven-section security coverage, then rerun only after separate GO TEST.

## Actual checks and exit codes

All relevant PHP diagnostic/test processes explicitly set `ICHES_DB_TESTS`, `ICHES_CMS_DB_TESTS`, `ICHES_CMS_LIFECYCLE_DB_TESTS`, `ICHES_CMS_UI_DB_TESTS`, `ICHES_CMS_PUBLIC_RENDER_DB_TESTS` to `0` and unset `ICHES_CMS_DB_SCHEMA` process-locally. No .env/global configuration change.

| Command | Exit | Observed result |
| --- | --- | --- |
| `php harness/evidence/ICH-S1-04/REV-01/diagnose.php` | 0 twice | 22 diagnostic checks; exact rejection reproduced, defect remains |
| `php artisan test --compact --no-ansi` | 0 | 141 passed / 1578 assertions |
| `php artisan test --compact --no-ansi tests/Feature/PublicCmsRenderingTest.php tests/Unit/PublishedSectionPresenterTest.php` | 0 | 27 passed / 384 assertions |
| `php -l tests/Database/PublicCmsRenderingTest.php` | 0 | No syntax errors; file unchanged, not executed as a test |
| `php vendor/bin/pint --test tests/Database/PublicCmsRenderingTest.php` | 0 | Passed; file unchanged |
| `php -l harness/evidence/ICH-S1-04/REV-01/diagnose.php` | 0 | No syntax errors |
| `git diff --check` | 0 | No tracked changes |

MySQL tests, migration, persistence writes and browser/build checks: NOT RUN. Production validator/security contracts unchanged. No before/after fix PASS or successful MySQL claim.

## Git handoff

Original HEAD remains `ae6d3f2314170146a80df84653874b01607b227f`; no new commit or source change. Working tree contains only three new untracked evidence files:

```text
harness/evidence/ICH-S1-04/REV-01/diagnose.php
harness/evidence/ICH-S1-04/REV-01/diagnostic-results.json
harness/evidence/ICH-S1-04/REV-01/diagnostic-report.md
```

Next gate: independent ChatGPT diagnostic review and explicit scope authorization for the production validator/regression fix; then separate GO TEST ICH-S1-04 REV-01. Only after verified MySQL PASS may separate GO PUSH & PR be considered. No push/PR/merge performed.
