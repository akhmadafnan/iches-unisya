# ICH-S1-03 — Backoffice Website CMS UI

30 October 2026, Asia/Jakarta. Owner explicitly authorized GO CODING ICH-S1-03, DB-free verification, sanitized evidence and exactly one local commit. External Work Order read in full: `D:/PINJAM-AFNAN/ICHES/WORK-ORDERS/ICH-S1-03-WORK-ORDER-PREPARED.md`. Its historical pending-GO label is superseded by the attached owner GO.

## Git and execution scope

Preflight PASS: DESKTOP-PE5D86C, official origin `https://github.com/akhmadafnan/iches-unisya.git`, clean main, HEAD and existing local origin/main both `d5d8249d67beeb3714458dd36aef27ca24902982`. Both S1-01 `253f5b5668027eb0ebf2ccea8a9f95478fbf0365` and S1-02 REV-01 `9b4801238cee614bb3e30a1655b2543fb1e64fe0` ancestry checks exit 0. No fetch/reset/rebase/amend. Created `feat/ich-s1-03-cms-backoffice-ui`. The single new local commit must have that baseline as its direct parent; final SHA and clean-tree check are reported after commit, avoiding a self-referential SHA in this evidence.

All 18 application paths are within the exact allowlist. Additional paths are only append-only `harness/build-log.md` and this task's evidence directory. No dependencies, CSS/theme, Auth/Workspaces/middleware, lifecycle/writer/policy, migrations, .env or governance files were modified. No push/PR/merge/deploy or S1-04.

## Implemented behavior

- Two new private Inertia GET routes: `/backoffice/website/pages` and `/backoffice/website/pages/{page}` under existing AuthPrivacy/auth/ValidateWorkspace. Event is derived exclusively from the current user's active selected assignment, restricted to event-wide Superadmin and checked by the existing CmsPagePolicy. Personal/null/malformed/revoked/foreign-user/institution/other-role contexts cannot access the screens. Page lookup additionally constrains persisted event ID. Query/body event/workspace fields cannot choose scope. Both reads return private/no-store and noindex/nofollow/noarchive headers.
- Index validates search/page, escapes LIKE wildcards, orders by slug, paginates 20 records and maps only page identity, slug, revision version labels and updated_at. It eager-loads revision metadata without snapshots. When any of the three CMS tables is absent, after authorization it renders a truthful unavailable state with no CRUD controls. No migration fallback, fabricated records or automatic schema changes.
- Editor exposes only the current authorized draft's validated v1 snapshot, page/publication IDs, the actual component catalog, and at most 50 revisions plus 50 audit records. History projections exclude snapshots, actor account data and file metadata. Other workspace choices remain the user's existing authorized shell choices. No private storage path, generated download URL, credential or public preview token is supplied.
- PageSnapshotValidator changes only by adding a read-only catalog accessor; existing validation behavior is unchanged. Vue consumes these server variants/field definitions for the five supported component types. There is no second component registry.
- Lyra Pages screen supports search, pagination, true JSON create, slug validation feedback, persisted status labels, empty/loading/error/schema-unavailable states. Existing shared sidebar adds Website CMS only for the eligible selected server-provided workspace; both desktop and mobile use it. Topbar shows CMS/editor breadcrumbs. Existing preparation translations no longer incorrectly state that website management is unavailable to everyone.
- Editor supports EN/ID/AR page title and per-section translation status/fields; missing uses null content. Add/duplicate/delete/reorder/visibility/variant controls retain UUID identity, contiguous positions, media references and allowed bindings. Countdown uses only Event Master starts_at binding, with no invented hour or live timer. Duplicate content is independent; max 100 sections. UUIDv4 generation uses cryptographic getRandomValues so the owner's HTTP local hostname does not require randomUUID's secure context.
- Save Draft/Preview/Publish/Restore use the five existing S1-02 JSON endpoints, same-origin credentials, Accept JSON, decoded Laravel XSRF-TOKEN header, no-store and no redirect following. Buttons/fields disable during mutation and server refresh. Dirty state and departure warning protect local edits; no success is shown before the server confirms the write. Refresh errors do not leave a permanently pending control. Backend validation stays authoritative.
- Preview saves unsaved changes first, then reads the private JSON endpoint and shows a read-only locale-aware structured modal, excluding hidden sections. It explicitly says this is not final public rendering. No iframe or raw HTML.
- Publish is disabled until the saved current draft has ready nonempty EN title, and requires confirmation. Restore selects a bounded historical revision, requires confirmation, and tells the user the draft is retained. Both send actual revision IDs and reload server state. 401/403/409/419/422/503, network and other server failures have localized messages; 503 says publication may already be committed and asks for a reload before retrying. A reload action is available for reconciliation.
- Deferred: public rendering/live visual preview, media upload/picker/editing, nav/footer persistence, SEO/OG/sitemap and editable conference time. No active fake controls for these capabilities. Existing media references remain unchanged.

## Actual verification commands

All five DB opt-ins were confirmed absent by name before tests: ICHES_DB_TESTS, ICHES_CMS_DB_TESTS, ICHES_CMS_LIFECYCLE_DB_TESTS, ICHES_CMS_DB_SCHEMA, ICHES_CMS_UI_DB_TESTS. phpunit.xml forces DB_CONNECTION=disabled. No real database connection was used by the executed tests or UI fixture.

| Command | Final exit | Result |
| --- | --- | --- |
| `php artisan test --compact --no-ansi` | 0 | PASS: 132 tests / 1334 assertions, including existing S1-01/S1-02 regression |
| `php artisan test --compact --no-ansi --filter=CmsBackofficeUiTest` | 0 | PASS: 6 DB-free tests / 218 assertions |
| `npm.cmd run typecheck` | 0 | PASS |
| `npm.cmd run build` | 0 | PASS: Vite 7.3.7, 3408 modules; actual CMS pages split into their own chunks |
| `php -l <path>` for the five PHP paths in the application inventory | 0 each | PASS |
| `php vendor/bin/pint <five PHP paths>` | 0 | Formatting applied only within scope |
| `php vendor/bin/pint --test <five PHP paths>` | 0 | PASS |
| `node harness/evidence/ICH-S1-03/editor-contract.mjs` | 0 | PASS: real frontend snapshot output accepted by PHP v1; section operations/UUID/limits/locales/nav/CSRF/error mapping |
| `node harness/evidence/ICH-S1-03/browser-fixture.mjs` | 0 | PASS: 76 synthetic fixture checks, 23 real browser screenshots; no database involved |
| `git diff --check` | 0 | PASS |

Initial development failures were test/fixture issues: Schema facade mock resolved the disabled connection until explicitly swapped; Node could not spawn Herd's php.bat directly until the native PHP binary was selected; native Enter required CR text in CDP; the fixture first checked a dialog dir attribute rather than inherited computed direction; one existing Arabic navigation label was corrected in the harness; initial scoped Pint detected changed-file line endings/import ordering. Final checks above pass. None required changing security contracts or excluded source. The fixture's all-in-one test bundle emits a >500 kB warning; the actual application production build uses separate CMS chunks and passes normally.

## Browser evidence and limits

The browser harness builds the actual Pages/Editor and shared shell/components using installed Vue/Vite/Tailwind. It runs installed headless Chrome on a temporary loopback fixture server with a fresh temporary profile. Every screenshot has a prominent **SYNTHETIC UI FIXTURE — NO DATABASE** banner. Inertia reload and JSON boundaries are deliberately mocked. This proves rendering and browser interaction, not actual Laravel session/DB persistence or successful scope changes.

Final 76 checks cover EN/ID/AR at 375/768/1440, horizontal overflow, direction, accessible form labels, private-preview modal bounds/focus and RTL portal direction, desktop/mobile shared CMS navigation, native trusted Enter reorder and heading focus, add/duplicate/delete, dirty state, six rendered error statuses, CSRF header, preview disclosure, publish/restore confirmation and request UUIDs, unavailable-schema disabled controls, unauthorized-role menu hiding, and no uncaught browser errors. Native Escape closes dialogs. Representative screenshots were visually inspected (English desktop, Arabic mobile, Arabic mobile preview). No manual keyboard/screen-reader UAT, full focus-loop/Tab audit or live authenticated database browser workflow is claimed.

One early fixture cleanup encountered a Windows EPERM and left a temporary profile directory. A later bounded shell attempt to remove `C:/Users/MCN Broadcast/AppData/Local/Temp/iches-s103-ui-OMaV0F` was rejected by automatic approval review with reason **blocked by policy**; no override/bypass was attempted. That non-repository temporary directory was left intact. The rejected combined command did not run; required lint/Pint/test checks were subsequently run separately and passed. Subsequent browser fixture runs cleaned their own temporary directories successfully.

## Database gate

**No migration, seeding, DB reset/rollback command, or MySQL opt-in test execution occurred. Development `iches_unisya` was not changed.** Existing isolated schema availability is not retested or modified by this task.

Three new strict-opt-in MySQL tests in `tests/Database/CmsBackofficeUiTest.php` are source-only and **BLOCKED_NOT_RUN** pending separate owner GO TEST ICH-S1-03. They require all five opt-ins, validate exact host/port/schema/FK mode, require tables already present, use synthetic @iches.test fixtures and roll back the outer transaction. Prepared scenarios cover real scoped listing/search/pagination, create/editor/current draft and audit projections/private preview/publish/restore with draft retention, duplicate/reserved slug errors, and foreign-event/foreign-user/institution/role/revocation denial. Existing 19 S1-01/S1-02 persistence tests were also NOT RUN in this coding task.

Local runtime is PHP 8.4.24 / Node 26.4.0; Node 22 remote CI remains NOT RUN. No fully verified database-backed CMS UI claim is made before the separate MySQL/browser gate.

## AC-01 through AC-16

PASS is limited to the evidence stated. Where the criterion depends on persisted runtime behavior, the unexecuted part is explicitly BLOCKED_NOT_RUN even when source and mocked contracts are present.

| AC | Status | Evidence / remaining verification |
| --- | --- | --- |
| AC-01 | PASS | Exact clean baseline/remote/device/ancestry and feature branch. |
| AC-02 | PASS | Exactly 18 allowed application paths; evidence only in permitted locations; one local commit verified in handoff. |
| AC-03 | BLOCKED_NOT_RUN | DB-free auth/policy/query denial and browser menu contract PASS; full persisted selected assignment/foreign-user/revocation matrix awaits MySQL GO. |
| AC-04 | BLOCKED_NOT_RUN | Scoped bounded listing/search/pagination implemented; real-record tests prepared, not run. |
| AC-05 | BLOCKED_NOT_RUN | Explicit scoped projections and safe unavailable-state Inertia props verified; actual persisted editor/history reads await MySQL GO. |
| AC-06 | PASS | Catalog accessor, exact five types/variants/field definitions and all variant snapshots validated by PHP DB-free tests. |
| AC-07 | BLOCKED_NOT_RUN | Real existing create endpoint wired, slug dialog/errors present; create/edit persisted workflow and duplicate/reserved slug UI verification require DB gate. |
| AC-08 | PASS | Actual helpers plus browser add/duplicate/delete/native keyboard reorder/focus; unique UUIDs/positions/limits and nested-copy independence checked. |
| AC-09 | PASS | EN/ID/AR frontend snapshots accepted by actual PHP validator; missing null, text/URL rejection and countdown binding checks. |
| AC-10 | PASS | Actual JSON client CSRF/credentials/redirect/no-store and six error statuses; browser dirty/error/save contracts. Real DB write-isolation verification remains separately gated. |
| AC-11 | PASS | Existing private endpoint reused, JSON-derived readonly preview, clear disclosure, hidden-section exclusion, no public route/iframe; actual authorized persisted read awaits DB gate. |
| AC-12 | BLOCKED_NOT_RUN | Existing lifecycle APIs wired with exact revision IDs, confirmation and refetch; synthetic browser contracts PASS, persisted pointer/draft/audit integration awaits DB GO. |
| AC-13 | PASS | Existing Lyra tokens/primitives/layout; synthetic browser 76 checks and screenshots at 375/768/1440 incl. Arabic RTL/dialogs/mobile and native reorder. Manual accessibility/live-data UAT not run. |
| AC-14 | PASS | No fake deferred controls; authenticated unavailable-schema Inertia tests in EN/ID/AR and browser fixture confirm no CRUD controls; no migrations. |
| AC-15 | PASS | All permitted final checks exit 0; new and existing MySQL opt-in tests explicitly NOT RUN. |
| AC-16 | PASS | Full commit/parent, 46-path inventory and clean tree verified in handoff after the one local commit; live DB browser QA explicitly blocked; no publication/next task. |

## Exact inventory — 46 changed paths

Application (18):

```text
app/Http/Controllers/CmsBackofficeController.php
app/Services/Cms/PageSnapshotValidator.php
routes/web.php
resources/js/components/navigation/AppSidebar.vue
resources/js/components/navigation/AppTopbar.vue
resources/js/i18n/locales/en.ts
resources/js/i18n/locales/id.ts
resources/js/i18n/locales/ar.ts
resources/js/types.ts
resources/js/pages/cms/Pages.vue
resources/js/pages/cms/Editor.vue
resources/js/components/cms/SectionEditor.vue
resources/js/components/cms/RevisionHistory.vue
resources/js/lib/cms/schema.ts
resources/js/lib/cms/editor.ts
resources/js/lib/cms/api.ts
tests/Feature/CmsBackofficeUiTest.php
tests/Database/CmsBackofficeUiTest.php
```

Evidence (28):

```text
harness/build-log.md
harness/evidence/ICH-S1-03/verification.md
harness/evidence/ICH-S1-03/editor-contract.mjs
harness/evidence/ICH-S1-03/browser-fixture.mjs
harness/evidence/ICH-S1-03/browser/results.json
harness/evidence/ICH-S1-03/browser/en-375.png
harness/evidence/ICH-S1-03/browser/en-768.png
harness/evidence/ICH-S1-03/browser/en-1440.png
harness/evidence/ICH-S1-03/browser/id-375.png
harness/evidence/ICH-S1-03/browser/id-768.png
harness/evidence/ICH-S1-03/browser/id-1440.png
harness/evidence/ICH-S1-03/browser/ar-375.png
harness/evidence/ICH-S1-03/browser/ar-768.png
harness/evidence/ICH-S1-03/browser/ar-1440.png
harness/evidence/ICH-S1-03/browser/en-preview-375.png
harness/evidence/ICH-S1-03/browser/en-preview-768.png
harness/evidence/ICH-S1-03/browser/en-preview-1440.png
harness/evidence/ICH-S1-03/browser/id-preview-375.png
harness/evidence/ICH-S1-03/browser/id-preview-768.png
harness/evidence/ICH-S1-03/browser/id-preview-1440.png
harness/evidence/ICH-S1-03/browser/ar-preview-375.png
harness/evidence/ICH-S1-03/browser/ar-preview-768.png
harness/evidence/ICH-S1-03/browser/ar-preview-1440.png
harness/evidence/ICH-S1-03/browser/en-navigation-375.png
harness/evidence/ICH-S1-03/browser/id-navigation-375.png
harness/evidence/ICH-S1-03/browser/ar-navigation-375.png
harness/evidence/ICH-S1-03/browser/ar-schema-unavailable.png
harness/evidence/ICH-S1-03/browser/ar-create.png
```
