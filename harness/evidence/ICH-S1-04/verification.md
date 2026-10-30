# ICH-S1-04 — Implementation verification and review handoff

30 October 2026, Asia/Jakarta. Explicit attached OWNER GO CODING ICH-S1-04 supersedes prepared/pending labels in both external work-order files and historical repository statuses. Both external files were read completely; implementation stays within their exact 20 application paths. No other task was started.

## Git and scope

Preflight PASS: clean `main`, HEAD/local main/existing origin/main all `11e90cf6343bfc776534a4c9795b8764353f7ad3`; official origin `https://github.com/akhmadafnan/iches-unisya.git`. No fetch or new remote verification claimed. Created `feat/ich-s1-04-public-section-rendering` only after the check. Exactly one local commit is authorized, with that baseline as direct parent. Final full SHA, parent, path count and clean-tree result are reported after commit rather than embedding a self-referential SHA here.

20 application paths; 14 evidence/log paths; 34 total. Only `routes/web.php` and the three locale files modify existing application source. Pint additionally reformatted existing route chains without changing their semantics. Existing policies/writer/lifecycle, migrations, CMS editor, auth/locale middleware/controller, Foundation, Blade noindex, global styles, dependencies, .env and governance files remain untouched.

## Implemented behavior

- Explicit guest `GET /events/{event:slug}/pages/{page:slug}`, named `public.cms.page`. Lowercase slug constraints; string controller parameters intentionally prevent implicit Eloquent binding before schema checks. Reader resolves persisted event by slug, page by that event ID and slug, then revision by page ID and `published_revision_id` only. Independent identity/schema checks fail closed. Missing tables, missing models, cross-event/page pointers, unsupported revision schema, corrupt/unsafe snapshot return 404; SQL query errors are converted to safe 404.
- Existing v1 validator is the field/variant contract. A separate presenter validates before caching, removes hidden sections, private media references and every unready translation. Output contains only allowlisted event facts, page slug/localized title/fallback flag, localized visible sections, safe resolved bindings and the existing catalog. No raw model/snapshot, drafts, private preview, storage/download data, actor/grants, audit/history or page list enters public props.
- Locale policy: use requested ready content; otherwise use ready English with a visible localized fallback notice and English `lang`/LTR wrapper. If neither exists, omit the section. Page requires ready English title. Ready Arabic content is RTL. Interface labels retain visitor language/direction even inside an English fallback section. No generated translations.
- Vue registry maps exactly five approved keys to coded SFCs. The PHP-backed browser contract verifies all catalog keys and variants: editorial-copy default/wide, hero default/split, event-essentials/default, countdown/default and CTA/default. Stored order and repeated component types remain intact. Unknown/prototype keys and unsupported variants render nothing. All CMS text uses Vue interpolation; no raw HTML, dynamic imports or user-selected component paths.
- Fresh Event Master supplies name/timezone/starts_at/ends_at; selected bindings are resolved independently of editorial cache. Dates have ISO8601 offsets in event timezone. Missing official time stays null with a localized notice; countdown only renders the bound time/notice. Synthetic test timestamps are explicitly not official conference hours.
- Exact `CmsPublicationLifecycle::cacheKey($page)` with five-minute TTL stores only validated, ready, locale-independent editorial projection and published revision identity. Every read checks the current persisted pointer and same-page revision; a late cache fill for an old publication cannot override a new pointer. Existing publish/restore invalidation continues to forget this same key. Event facts, visitor/session/grants, draft and media are never cached. Cache does not remove DB availability checks.
- Minimal separate Academic Editorial layout: ivory/forest/near-black/gold, existing Inter/Sora/Arabic fonts, semantic h1/h2, responsive split/wide variants, visible keyboard focus and skip-to-main. No assets or business links were invented. CMS CTA href comes exclusively from the validated publication.
- Existing locale endpoint sets session but redirects to auth destinations. Public language selector posts with same-origin credentials, CSRF cookie header and manual redirect, then reloads the current Inertia page. It never follows that auth redirect; failed POST restores selection and displays localized error. PHP tests verify existing endpoint/session behavior; browser fixture intercepts transport and tests reload/error handling. This is not a live Laravel/browser session test.
- Responses remain private/no-store and noindex/nofollow. Existing Foundation root and global Blade robots tag are preserved. No SEO/indexing readiness claim.

## Executed verification

Local PHP 8.4.27, Node 26.4.0. PHP 8.4/Node 22 CI remains a later push/PR gate. All six opt-in variables were confirmed absent by name; phpunit.xml forces disabled DB, array cache/session. Default suite includes Feature only; the focused command explicitly includes the new Unit file. No Database test file was executed, even to obtain a skip count.

| Command | Actual final exit | Result |
| --- | --- | --- |
| `php artisan test --compact --no-ansi` | 0 | PASS: 141 tests / 1578 assertions |
| `php artisan test --compact --no-ansi tests/Feature/PublicCmsRenderingTest.php tests/Unit/PublishedSectionPresenterTest.php tests/Feature/CmsLifecycleSecurityTest.php tests/Feature/CmsSnapshotContractTest.php tests/Feature/CmsBackofficeUiTest.php` | 0 | PASS: 129 CMS/security tests / 1034 assertions |
| `npm.cmd run typecheck` | 0 | PASS |
| `npm.cmd run build` | 0 | PASS: 3427 modules; public CMS chunk about 6.27 kB before gzip |
| `php -l <path>` for all seven changed application PHP paths plus `harness/evidence/ICH-S1-04/fixture.php` | 0 each | PASS: eight syntax checks |
| `php vendor/bin/pint <seven application PHP paths>` | 0 | Scoped formatting only |
| `php vendor/bin/pint --test <same seven paths>` | 0 | PASS |
| `node harness/evidence/ICH-S1-04/browser-fixture.mjs` | 0 | PASS: 131 checks / nine full-page screenshots |
| `git diff --check` | 0 | PASS |

Initial verification exposed an invalid draft CTA URL in synthetic fixtures, a missing Inertia `toResponse` call before setting headers, DB-free fixture date-format lookup and Vue dynamic-component inference. These were corrected within scope. Initial PHP/TS checks failed (exit 1; direct PHPUnit diagnostic exit 2); they were not counted as passes. A later typecheck found unsupported `preserveScroll` on `router.reload`; reload already preserves scroll internally, so that option was removed. Final checks above were run individually and passed. No expected security result was weakened.

The existing CMS lifecycle security tests exercise real transaction-manager after-commit behavior with mocked PDO/SQL, including publish/restore invalidation and rollback. New reader tests exercise warmed-cache draft exclusion, fresh event facts, invalidation/refill and changed-pointer handling without persistence. The new MySQL source test additionally invokes registered real invalidation callbacks inside a rolled-back fixture; it explicitly does not claim an actual DB commit.

## Browser evidence and limits

Actual installed headless Chrome renders the real public page/layout/five section components and existing i18n. Fixtures are generated by the real PHP validator and presenter using synthetic Event/snapshot instances; PHP does not boot the application or connect to DB. Every screenshot displays `SYNTHETIC UI FIXTURE — NO DATABASE`. All EN/ID/AR combinations at 375/768/1440 pass overflow, direction, heading hierarchy, seven variants, stored/repeated order, private/hidden/draft exclusion, fallback notice/content direction, UI label direction, CTA and native keyboard skip/focus checks. Unknown component/prototype key, unknown variant, synthetic ISO bound start, CSRF/manual-redirect/reload and failed locale request also pass. No uncaught browser exceptions. Full-page screenshots were visually inspected at Arabic/mobile and English/desktop.

See `browser/results.json` for each of 131 executed assertions. Screenshots and transport mocks prove synthetic rendering/behavior only. Live Laravel/MySQL rendering, actual browser session locale switching, persisted CMS editor-to-public UAT and production hosting remain BLOCKED_NOT_RUN.

## Acceptance criteria — independently assessed

| AC | Status | Evidence / limit |
| --- | --- | --- |
| AC-01 | PASS | Exact clean official local base and dedicated feature branch verified before edits. |
| AC-02 | PASS | 20 allowlisted application paths + 14 evidence/log paths; one local commit only, final inventory/clean check after commit. |
| AC-03 | PASS | Guest HTTP tests execute actual route/controller/reader with scoped query boundaries mocked; persisted lookup implemented. |
| AC-04 | PASS | Missing event/page/revision/table, corrupt schema/snapshot and foreign identities reject; warm-cache denial covered. Real MySQL cases remain unexecuted. |
| AC-05 | PASS | Ready-only allowlist projection and cache tests remove private media/drafts/metadata; no account/workspaces shared by public response. |
| AC-06 | PASS | All five real SFCs/all seven variants checked against PHP catalog; unknown/prototype/variant browser rejection. |
| AC-07 | PASS | PHP filter/order/duplicate tests plus browser order/variant equality with projected publication. |
| AC-08 | PASS | Ready EN/ID/AR and explicit English fallback/omission tests; Arabic RTL, selected-language UI direction and keyboard browser checks. |
| AC-09 | PASS | Four server facts, missing/null time and synthetic offset-aware update tests; no ticking/fake clock. |
| AC-10 | PASS | Validator rejects unsafe CTA/HTML/domain binding; interpolated components; no private media projection. |
| AC-11 | PASS | Same lifecycle key, bounded TTL, revision-pointer checks, event freshness, reader invalidation contract and existing DB-free afterCommit regression. Actual MySQL execution deferred. |
| AC-12 | PASS | Separate editorial shell; real component synthetic 375/768/1440 screenshots, RTL/heading/focus/overflow assertions. |
| AC-13 | PASS | Foundation regression and noindex assertion; original Blade unchanged, new response robots header. |
| AC-14 | PASS | Final DB-free, security, typecheck/build, lint, scoped Pint and whitespace checks all exit 0. |
| AC-15 | BLOCKED_NOT_RUN | Source and syntax reviewed: five boolean opt-ins plus exact isolated schema required before connection; existing migrations/FKs checked; rollback-only fixtures. Three persistence tests intentionally NOT RUN without separate GO TEST. |
| AC-16 | PASS | 131 actual browser synthetic checks/nine labeled screenshots; no real persisted browser UAT claim. |
| AC-17 | PASS | This inventory/evidence plus final post-commit SHA/parent/clean-tree handoff; stop after one local commit for independent review. |

## Database and pending work

Migration NOT RUN. MySQL persistence tests NOT RUN. No seeding/schema changes, DB reset, DB rollback command or writes to development `iches_unisya`. Source-only guarded test targets `iches_unisya_cms_test` and checks host/port/database/FKs/already-recorded migrations before any fixture write, then uses transactions/rollback. No environment or global toolchain changed.

Pending: independent ChatGPT source review; separate GO TEST ICH-S1-04; GO PUSH & PR with PHP 8.4/Node 22 CI; GO MERGE exact head; separately authorized sync. S1-05 homepage redesign, S1-06 ticking timer and S1-07 public media/SEO remain deferred. No push/PR/merge/deployment executed.

## Complete changed-path inventory (34)

```text
app/Http/Controllers/PublicCmsPageController.php
app/Services/Cms/PublishedCmsPageReader.php
app/Services/Cms/PublishedSectionPresenter.php
routes/web.php
resources/js/pages/public/CmsPage.vue
resources/js/components/public/PublicCmsLayout.vue
resources/js/components/public/SectionRenderer.vue
resources/js/components/public/sections/HeroSection.vue
resources/js/components/public/sections/EditorialCopySection.vue
resources/js/components/public/sections/EventEssentialsSection.vue
resources/js/components/public/sections/CountdownSection.vue
resources/js/components/public/sections/CtaSection.vue
resources/js/lib/public-cms/registry.ts
resources/js/lib/public-cms/types.ts
resources/js/i18n/locales/en.ts
resources/js/i18n/locales/id.ts
resources/js/i18n/locales/ar.ts
tests/Feature/PublicCmsRenderingTest.php
tests/Unit/PublishedSectionPresenterTest.php
tests/Database/PublicCmsRenderingTest.php
harness/build-log.md
harness/evidence/ICH-S1-04/verification.md
harness/evidence/ICH-S1-04/fixture.php
harness/evidence/ICH-S1-04/browser-fixture.mjs
harness/evidence/ICH-S1-04/browser/results.json
harness/evidence/ICH-S1-04/browser/en-375.png
harness/evidence/ICH-S1-04/browser/en-768.png
harness/evidence/ICH-S1-04/browser/en-1440.png
harness/evidence/ICH-S1-04/browser/id-375.png
harness/evidence/ICH-S1-04/browser/id-768.png
harness/evidence/ICH-S1-04/browser/id-1440.png
harness/evidence/ICH-S1-04/browser/ar-375.png
harness/evidence/ICH-S1-04/browser/ar-768.png
harness/evidence/ICH-S1-04/browser/ar-1440.png
```
