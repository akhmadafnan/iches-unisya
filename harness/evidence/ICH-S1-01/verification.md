# ICH-S1-01 local verification — 30 October 2026

Owner attachment explicitly grants GO CODING ICH-S1-01, bounded tests and exactly one local commit, superseding historical PENDING GO. Definitive external work order read in full. No database-operation, publication or subsequent-task GO.

## Preflight and inventory

- Device: DESKTOP-PE5D86C; canonical origin: https://github.com/akhmadafnan/iches-unisya.git.
- Initial main and origin/main: `6261e69760a3cff9a2d61260cff0a8670fab2d06`; clean tree; UI-01D `d5d10b197ef3cdb3e8701ec6772d9ff44fa8166b` ancestor check exit 0. No fetch or synchronization.
- Branch: `feat/ich-s1-01-cms-persistence`; intended sole commit parent is the exact initial main SHA. Final commit SHA is reported in the handoff because a commit cannot contain its own SHA.
- Local PHP 8.4.24, Node 26.4.0; Node 22 CI has NOT RUN. No runtime installation or configuration change.

Exactly seven new allowlisted application paths:

1. `database/migrations/2026_10_30_000005_create_cms_pages_and_revisions.php`
2. `app/Models/CmsPage.php`
3. `app/Models/CmsPageRevision.php`
4. `app/Services/Cms/PageSnapshotValidator.php`
5. `app/Services/Cms/PageRevisionWriter.php`
6. `tests/Feature/CmsSnapshotContractTest.php`
7. `tests/Database/CmsPersistenceTest.php`

Two evidence paths (nine total): append-only `harness/build-log.md` and this file. No changes to routes, Auth, Policies, dependencies, UI, CSS, previous migrations, seeders, .env, governance or skills.

## Implementation and source inspection

Two new InnoDB tables with UUIDv7 Eloquent identifiers; event FK, per-event slug uniqueness, per-page version uniqueness, required creator FK, schema-versioned JSON and creation timestamp. Nullable draft/published pointers initially empty. Composite `(id, revision_pointer)` FKs target unique `(page_id, id)` to reject orphan/cross-page/cross-event pointers. Restrictions prevent deleting referenced history, pages, users or events. Forward-only down() throws pending an owner-reviewed recovery plan. No DDL was executed; MySQL compatibility and actual constraints remain unverified at runtime.

Page model validates normalized lowercase slugs against current route prefixes/names and reserved platform names. Event/page identity and published pointer cannot change through its instance save API. Revision model blocks existing-instance save/update, delete, quiet equivalents and counter mutations; mass assignment is guarded. This is an internal application boundary, not database-level append-only permissions: raw SQL/base query-builder writes can bypass model guards. Future web callers must use the writer behind S1-02 Policies/Gates; no such caller exists here.

Snapshot schema v1 requires EN/ID/AR statuses missing/draft/ready and null content for missing translations. Sections carry stable UUID, contiguous explicit order, strict visibility, registered component/variant, typed locale content, media UUIDs and domain bindings. Initial small registry is replaceable through the validator constructor by new coded schemas. Limits: 256 KiB JSON, depth 32, 100 sections, 20 media IDs and 10 bindings per section, 5,000 characters per text and 2,048 bytes per URL. Unknown keys/types, raw/encoded HTML, script schemes, executable expressions, control bytes, unsafe links and malformed/object-as-list payloads are rejected. Fields are plain text or safe links; arbitrary HTML/CSS/JS fields are absent. Future rendering must escape text and must not interpret it as HTML.

Only implemented Event Master fields can be bound (`name`, `timezone`, `starts_at`, `ends_at`); context supplies the event, never editorial overrides. Countdown requires the starts_at binding with no date/hour fallback. Other domains require future typed contract extension; no synthetic official data is created.

Internal saveDraft validates before any write, requires persisted actor/event/page, reloads and locks the actual page, verifies event scope, validates private same-event cms_image metadata, then appends a revision and advances only the draft pointer in one transaction. A locking current read of the latest revision avoids stale consistent-read MAX values under MySQL REPEATABLE READ. Unique version constraint and three deadlock attempts backstop serialization. No publish method, HTTP endpoint, public/private URL generation or physical media operation.

## Executed verification

| Command | Exit | Actual result |
|---|---:|---|
| `npm.cmd run typecheck` | 0 | PASS |
| `npm.cmd run build` | 0 | PASS; Vite 7.3.7, 3382 transformed modules |
| `php artisan test --compact --no-ansi` | 0 | PASS; final 113 tests, 868 assertions, default DB connection disabled |
| `php artisan test --compact --no-ansi tests/Database/CmsPersistenceTest.php` | 0 | **BLOCKED_NOT_RUN**; all 7 tests skipped, 0 assertions, skip before DB connection |
| `php vendor/bin/pint <seven allowlisted PHP paths above>` | 0 | Formatting completed, scoped only |
| `php vendor/bin/pint --test <seven allowlisted PHP paths above>` | 0 | PASS |
| `git diff --check` and final staged equivalent | 0 | PASS |

CMS contract/guard tests add 83 tests / 184 assertions to the existing 30 tests / 684 assertions. Cases cover missing and Arabic translations, extensible schemas, visibility/order, empty pages, malformed and oversized JSON, locale/type/field rejection, URLs/XSS, domain overrides, required countdown binding, route slugs, UUIDv7, publication/page identity guards and revision immutability. Feature tests explicitly prohibit DB connection through the DB facade.

Persistence source prepares seven tests covering scoped slugs, null start time, pointer FKs, orphan/creator restrictions, version/author history, publication isolation, cross-event spoofing, invalid actor/XSS rollback, valid private media and invalid metadata. The concurrency test prepares an independent-connection lock timeout plus sequential version verification inside rolled-back fixtures; it is not a completed simultaneous multi-writer stress test.

Persistence opt-in requires separate owner authorization, both ICHES_DB_TESTS=1 and ICHES_CMS_DB_TESTS=1, and ICHES_CMS_DB_SCHEMA=iches_unisya_cms_test. It connects only to loopback MySQL on port 3306 and the dedicated, already migrated `iches_unisya_cms_test` schema using process-local connection config; checks selected DB, FK enforcement and tables before beginning a fixture transaction. No schema creation, migration, seeding, automatic SQLite fallback, committed fixtures or real dev-schema writes. Authorized operator must provision/review the test schema separately. No opt-ins were enabled in this run.

## AC matrix

| AC | Local status | Evidence / remaining gate |
|---|---|---|
| 01 | PASS | Exact clean main, origin, ancestry, device and branch verified |
| 02 | PASS | Seven allowlisted application paths; two permitted evidence paths |
| 03 | SOURCE READY / DB BLOCKED | Two-table schema and composite FKs inspected; not applied |
| 04 | SOURCE + GUARD TEST PASS / DB BLOCKED | Append guards, page/current-revision locks, version unique; DB tests not run |
| 05 | PASS SOURCE + GUARD TEST / DB BLOCKED | No publish API; writer changes draft only; persistence assertion prepared |
| 06 | PASS | Typed, extensible, ordered EN/ID/AR contract tests |
| 07 | PASS | Negative malformed/oversized/schema/HTML/URL/domain tests |
| 08 | PASS | Event bindings, null-hour preservation, no official-data fixtures in app |
| 09 | SOURCE READY / DB BLOCKED | Same-event private media metadata checks; no URL exposure; DB negatives prepared |
| 10 | PASS | No routes/UI/Auth/i18n changes; existing Feature tests and frontend checks pass |
| 11 | PASS PERMITTED CHECKS | Exact command results above; persistence explicitly blocked; local Node 26, Node 22 CI pending |
| 12 | LOCAL HANDOFF | One local commit, exact parent and clean tree verified and SHA reported after commit |

Migration: **NOT APPLIED**. Database persistence/concurrency: **NOT RUN / BLOCKED**. Independent review, actual SQL application, database acceptance and Node 22 CI remain pending. No browser QA required for this data-only slice. No push, PR, merge, reset, rollback command, seeding, deployment or S1-02 work.
