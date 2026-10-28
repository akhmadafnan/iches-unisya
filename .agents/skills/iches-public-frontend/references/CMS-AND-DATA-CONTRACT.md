# ICHES Website CMS & Countdown Implementation Contract (Agent reference)

**Requirement:** Public website must be **managed through Backoffice → Website / CMS**, including adding **new pages, flexible sections, menu items, media and content**, beyond editing a fixed homepage. This is a functional product requirement, while the exact UI and physical database model remain draft. No requirement for an unbounded arbitrary JavaScript/HTML builder.

## CMS navigation proposal (revisable)

Website CMS → Overview, Pages, Page Sections, Component Library, Navigation & Footer, Media Library, Speakers & Partners (or related modules), Event Countdown, Translations EN/ID/AR, SEO & Social, Draft/Preview/Publish, Revision History, Audit.

Backoffice uses shadcn-vue; page frontend uses original editorial design. Authorized Content Editor can draft, Publisher/Admin can publish if role separation chosen; no access to Finance Verified, Bank Settings, Academic ACCEPT or Final ACC merely through CMS.

## Data ownership

| Source | Owns | CMS may do |
|---|---|---|
| Event Master | event date/time/venue/timezone/status, selected countdown target | choose display/label/target binding, **not contradict source** |
| Conference Settings | CFP dates, abstract stage, track list | decide visibility/CTA placement, not bypass backend gates |
| Programs | agenda, offerings, registration state, package master | compose editorial program blocks, not invent fees |
| Speakers/Partners approved records | genuine name/affiliation/photos/consent/visibility | curate order/design only if published |
| Publication Outlets | OJS proceedings/journal list, selected/accepted downstream facts | show accurate status; no invented journals |
| Website CMS | page copy, editorial photos, rich text, section order, supported variants, navigation, SEO | draft/publish/rollback content |
| Finance | one verified bank account, invoice status, payment gate | published instructional content only from authorized data |

**Rule:** Store references to domain records instead of copying an editable amount or deadline into a rich-text block.

## Suggested component registry (extendable; NOT A LOCKED LIST)

Typed section registry: hero, event-essentials, countdown, about/editorial-copy, submission-journey, timeline, tracks, programs, speakers, partners, publication, downloads, FAQ, CTA, contact, bespoke new blocks as needed.

Each section record supports component key, approved schema version, layout variant, position, visible flag, responsive media/settings, localized content, media IDs, domain-binding reference IDs. Full custom sections require a new coded renderer and editor schema **once**; afterward Admin can reuse them with no deploy for content changes. Never represent this as unlimited no-code.

**Recommended minimal persistence:** page records + immutable page revision snapshot (including typed sections), media records reusing storage where possible, navigation settings, publish audit and locales. Do not assume seven independent CMS tables or automatically transform 38+15 previous database candidates into a fixed count. Set up cache invalidation per published page.

## Editing lifecycle

- Pages can be created, given slug + SEO settings and localized content. URLs require collision and reserved-route validation.
- Add, duplicate, reorder, hide, delete sections, switch supported layout variants and associate media.
- Draft → Preview (isolated authorized preview, no indexing) → Publish atomically → public cache invalidated → Restore previous revision with audit.
- Edits must be accessible without drag-and-drop; allow Move Up/Down for keyboard/mobile.
- Navigation/footer editable, including per-locale labels and visibility; role-based user routes never become public content via CMS.
- EN/ID/AR fields each have completion status; no silent automated translation, fallback as explicitly configured.
- Public SSR/SEO only surfaces **published** content. No data leakage in Inertia shared props.

## Required countdown

### Source and configuration

- **Count down to the actual start of main ICHES conference on January 5, 2027**, when official local start time is entered in Event Master. Event continues with related program January 6, 2027 per TOR; **do not mix the second day as default countdown target**.
- Use an **Admin-managed timezone-aware datetime**, for example `event.starts_at` or `countdown.target_event_reference` resolving to `starts_at`; timezone is `Asia/Jakarta` with explicit offset in serialized timestamp. **Start hour has not been finalized**; do not assume `00:00:00`.
- Countdown fields in CMS: enabled, title/subtitle EN/ID/AR, display variant, optional reference to another officially scheduled activity, post-start message, visibility section, design tokens.
- If exact start time is missing, show trustworthy conference date and **“Countdown activates when official start time is set”** or owner-approved neutral notice; do not display knowingly fabricated ticking timer.
- When target is known, calculate with absolute timestamps: `remainingSeconds = max(0, floor((targetEpochMs - nowEpochMs) / 1000))`; days=floor(sec/86400), hours=floor((sec%86400)/3600), minutes=floor((sec%3600)/60), seconds=sec%60. Do not parse ambiguous date strings.
- Update visually each second without creating hydration mismatch on SSR; honor reduced motion and screen-reader announcement frequency (not announcing each second).
- At zero, **stop the timer** and show “Event has started” / event status; never negative counts, never pretend a future event is finished. After finish, use explicit Event Master state.
- Admin date/time change must propagate correctly and invalidate page cache; every second refresh does not require polling the API.

### Countdown test cases

Future target 30 days; same-day >0; exact boundary; target past; timezone Asia/Jakarta while viewer in another timezone; page hydrated SSR; Admin changes target; blank target; document hidden tab resumes; Arabic RTL labels; server clock/client clock drift (if server_now supplied); timer cleanup on navigation.

Do not copy AICIS visible “00 Days” zeros as an implementation pattern when its date remains in the future: websites can render placeholder values in text-only crawlers. Implement actual calculation and tests.

## CMS safety

- Server validates content against component schema and URL/media allowlist.
- Rich text sanitization; no raw scripts, arbitrary HTML/CSS, unsafe iframe or SVG uploads by default.
- Private bank/academic files must never appear in public media library or page preview.
- Published speaker/partner photos require rights and official approval.
- Preserve author/editor/publisher and revision audit; role switch and support-view mode still enforce policies.
- Image optimization, accessible alt, focus and layout shift prevention.
