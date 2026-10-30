# ICH-S1-02 local implementation and verification

Date: 30 October 2026 (Asia/Jakarta). Owner explicitly supplied GO CODING ICH-S1-02, including exactly one local commit. The attached GO supersedes the external Work Order's historical pending status. Work Order read in full: `D:/PINJAM-AFNAN/ICHES/WORK-ORDERS/ICH-S1-02-WORK-ORDER-PREPARED.md`.

## Preflight and boundaries

- Device: DESKTOP-PE5D86C. Official origin: `https://github.com/akhmadafnan/iches-unisya.git`.
- Initially clean `main`; HEAD and existing local `origin/main` both `473962b214d7c623cf2df9fc7e8f204511a12663`. S1-01 `253f5b5668027eb0ebf2ccea8a9f95478fbf0365` ancestor check exit 0. No fetch performed.
- Branch: `feat/ich-s1-02-cms-publication`. Expected direct commit parent: `473962b214d7c623cf2df9fc7e8f204511a12663`. Final SHA is reported in the post-commit handoff; this document cannot embed its own containing commit SHA.
- Migration 000006: **NOT APPLIED** to any schema. No migration/seed/reset/rollback commands or DB opt-in execution. No .env/toolchain/dependency/UI/governance changes, push, PR, merge, deploy or next-task work.
- Local runtime: PHP 8.4.24, Node 26.4.0. Node 22 remote CI not run.

## Implementation and HTTP contract

`CmsPagePolicy` is discovered by Laravel conventions. Each ability requires an exact non-null `assignment:<id>` selected workspace. The existing Workspaces query checks the real user's active grant; policy additionally restricts event, null institution and role `superadmin`. Locking the grant within write/preview transactions serializes authorization with revocation. Null/personal/malformed workspaces are rejected before the existing null-workspace wildcard behavior can run.

`PageRevisionWriter::saveDraft(actor, event, page, snapshot, workspace)` now requires the explicit workspace argument. It verifies persisted actor/event, reloads and locks the actual page, checks persisted scope and authorizes inside the transaction before snapshot/media validation and insertion. Existing private media checks remain intact and are reused for preview/publication. Page lock plus locking latest-version read serializes revision numbering. The published pointer remains untouched. All three S1-01 test call sites were updated, and valid Superadmin assignment fixtures added without removing negative assertions.

Five JSON endpoints under existing AuthPrivacy + auth + ValidateWorkspace:

| Method | Path relative to `/workspace/events/{event}/cms/pages` | Input | Success |
| --- | --- | --- | --- |
| POST | `/` | `slug` | 201 page ID/slug |
| POST | `/{page}/draft` | `snapshot` | 201 revision metadata |
| GET | `/{page}/preview` | none | 200 revision metadata + validated draft snapshot |
| POST | `/{page}/publish` | UUID `revision_id` matching the current draft | 200 revision metadata |
| POST | `/{page}/restore` | UUID `revision_id` of an existing same-page immutable revision | 200 revision metadata |

Event route binding is server-side. Page lookups explicitly constrain persisted event ID. Request event/workspace fields cannot replace route/session authority. Authorization failures return 403; guests requesting JSON return 401; missing/cross-event pages or cross-page restore targets return 404; input/snapshot/unready EN titles return 422; missing drafts, stale publish IDs, already published targets and restore without an existing publication return 409. Duplicate event/slug creation becomes 422. Preview returns no stored-file model, private path or generated URL. Headers: private/no-store, noindex/nofollow/noarchive. Other locales retain their original statuses; no implicit translation or published fallback.

`CmsPublicationLifecycle` reloads actor/event/page, locks the page and authorizes in its transaction. It validates the snapshot/private media again and requires ready nonempty EN title. The only trusted query-builder publication write updates the locked, scoped page's published pointer and updated_at; existing CmsPage/CmsPageRevision guards are unchanged. The same transaction appends exactly one actor/from/to/action/time ledger row. Restore preserves the draft and all snapshots. Same-target retries produce 409 rather than duplicate audit rows.

The additive audit migration uses UUID IDs, actor/page restrictive FKs and both same-page composite revision FKs. `down()` is forward-only. The audit model rejects instance save/update/delete/counter mutation. Query-builder writes and raw SQL with sufficient database privileges can bypass model guards; database permissions remain necessary.

Cache contract for future renderers: `cms:published:<persisted-event-uuid>:<persisted-page-uuid>`, available via `CmsPublicationLifecycle::cacheKey`. Only publish/restore register one `Cache::forget` callback after outer commit. No global flush. If cache invalidation throws after commit, the exception is reported and HTTP 503 explicitly says publication committed and asks the caller to reload before retrying. Cache repair/retry infrastructure and a public renderer are outside this slice; cache invalidation cannot roll back an already committed publication.

## Actual commands and results

All four process opt-in variables were absent before persistence-gate verification: ICHES_DB_TESTS, ICHES_CMS_DB_TESTS, ICHES_CMS_LIFECYCLE_DB_TESTS, ICHES_CMS_DB_SCHEMA. phpunit.xml forces DB_CONNECTION=disabled for DB-free tests. No credentials or environment contents were printed.

| Command | Exit | Result |
| --- | --- | --- |
| `npm.cmd run typecheck` | 0 | PASS |
| `npm.cmd run build` | 0 | PASS, Vite 7.3.7 |
| `php artisan test --compact --no-ansi` | 0 | PASS, final 126 tests / 1116 assertions |
| `php artisan test --compact --no-ansi --filter=CmsLifecycleSecurityTest` | 0 | PASS, final targeted run 13 tests / 248 assertions |
| `php -l <path>` for each of the ten PHP paths below | 0 each | PASS |
| `php vendor/bin/pint <ten PHP paths>` | 0 | PASS |
| `php vendor/bin/pint --test <ten PHP paths>` | 0 | PASS |
| `git diff --check` | 0 | PASS |
| `php artisan route:list --path=workspace/events --except-vendor --no-ansi` | 0 | PASS, five new CMS endpoints and two existing scoped endpoints |
| `php vendor/bin/phpunit --no-progress --colors=never tests/Database/CmsPublicationLifecycleTest.php tests/Database/CmsPersistenceTest.php` with all opt-ins absent | 0 | Gate check only: all 19 tests SKIPPED / 0 assertions. Persistence remains BLOCKED_NOT_RUN, never PASS. |

Early development runs of the added callback test failed because its mocks did not yet initialize Laravel's query grammar/transaction manager/savepoint rollback. Those test harness errors were corrected; final scoped and complete suites pass. No application security guard was weakened. Scoped Pint normalized existing changed files' line endings; no unrelated files were formatted.

DB-free tests exercise real middleware/auth/CSRF, Laravel policy discovery, null/personal/spoofed workspace denial, exact event/role/institution query restrictions, response headers/metadata, audit instance guards and cache identity. The five callback cases run real Eloquent/service/Gate and Laravel transaction callbacks against a mocked PDO/SQL boundary: successful publish and restore, audit failure for each, and cache failure after commit. They verify callback order, rollback invocation, unchanged draft update contract, single ledger insert and targeted invalidation. They do **not** establish actual MySQL atomicity or isolation. The preview response test deliberately substitutes transaction payloads and proves response privacy only; persisted authorized reads require MySQL verification.

Twelve new strict-opt-in MySQL tests are prepared for: full create/draft/preview/publish/restore HTTP flow; role/revocation/event/institution/foreign-user denial; direct writer revocation; cross-event/page protection; direct lifecycle reauthorization; empty/stale/duplicate/unready conflicts; audit-failure rollback; deferred cache callback discard on rollback; invalid snapshot/HTTP inputs; persisted unsafe-snapshot revalidation; composite ledger FKs; and competing-connection lock plus stale publication denial. Seven existing S1-01 tests remain. Fixtures use a rollback-only outer transaction, never auto-migrate or commit data. Full real concurrent request stress and successful real outer-commit/cache behavior are not claimed by this rollback-only harness.

## Acceptance matrix

PASS means the stated check actually ran successfully. SOURCE/CONTRACT means implementation and DB-free proof are present but the real database outcome is **BLOCKED_NOT_RUN**.

| AC | Status | Evidence / remaining boundary |
| --- | --- | --- |
| AC-01 | PASS | Exact clean main/origin baseline, official remote/device and S1-01 ancestor; isolated branch. |
| AC-02 | PASS | Exactly ten allowlisted application paths plus this evidence and append-only build log. No excluded source/config/data. |
| AC-03 | SOURCE/CONTRACT | Policy discovery/null/spoofed workspace rejection and exact scoped grant query PASS. Full persisted role/revocation/foreign-user HTTP matrix blocked. |
| AC-04 | SOURCE/CONTRACT | Writer Gate inside locked transaction; call-site/fixture adaptation; original negative cases retained. MySQL writer regression blocked. |
| AC-05 | SOURCE | Additive UUID/composite FK audit schema, forward-only migration and DB-free immutable model guards PASS. Schema not applied; FK runtime blocked. |
| AC-06 | SOURCE/CONTRACT | Real service transaction API and audit/cache callback contract PASS with mocked SQL; actual atomic MySQL state/audit proof blocked. |
| AC-07 | SOURCE/CONTRACT | Restore transaction contract PASS; draft omitted from publication update; same-page target lookup. MySQL historical state proof blocked. |
| AC-08 | SOURCE/CONTRACT | Auth denial, JSON whitelist and private/no-store/noindex HTTP response PASS; actual draft read authorization matrix blocked. |
| AC-09 | SOURCE/CONTRACT | Existing snapshot validator suite unchanged/PASS, lifecycle-ready EN check and pointer isolation implemented; persisted statuses/state tests blocked. |
| AC-10 | SOURCE/CONTRACT | Target key, post-commit callback order, rollback/no-invalidation and cache-failure response PASS with mocked SQL; real DB commit boundary blocked. |
| AC-11 | PASS (DB-free) | Five routes require auth + ValidateWorkspace, CSRF retained; existing auth/workspace/private-file/Foundation regression suite PASS. |
| AC-12 | PASS | Final 126/1116 PHP suite, typecheck/build, ten PHP lints/scoped Pint and diff check exit 0; S1-01 contract file unchanged. |
| AC-13 | BLOCKED_NOT_RUN | Twelve lifecycle + seven adapted S1-01 MySQL tests skipped before connecting; separate owner migration/schema/test approval required. |
| AC-14 | Local handoff | One local commit requested; post-commit SHA, direct parent, 12-path inventory and clean tree verified/reported after creating it. No publication actions. |

## Exact changed paths

Ten application paths:

1. `database/migrations/2026_10_30_000006_create_cms_publication_events.php`
2. `app/Models/CmsPublicationEvent.php`
3. `app/Policies/CmsPagePolicy.php`
4. `app/Services/Cms/CmsPublicationLifecycle.php`
5. `app/Services/Cms/PageRevisionWriter.php`
6. `app/Http/Controllers/CmsLifecycleController.php`
7. `routes/web.php`
8. `tests/Feature/CmsLifecycleSecurityTest.php`
9. `tests/Database/CmsPublicationLifecycleTest.php`
10. `tests/Database/CmsPersistenceTest.php`

Evidence exceptions:

11. `harness/build-log.md` (append only)
12. `harness/evidence/ICH-S1-02/verification.md`

Independent ChatGPT review, owner acceptance, MySQL schema/test permission and later publication gates remain separate. No full database lifecycle verification or CMS UI/public renderer completion is claimed.
