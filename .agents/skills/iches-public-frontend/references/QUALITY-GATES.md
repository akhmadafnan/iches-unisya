# ICHES Public Frontend & CMS — Acceptance Gates for AI

**Run after building, not after merely writing specifications.** Report verified test commands, browser viewport checks and exact failures. Do not say “PASS” for a plan.

## Visual & content gate

- [ ] Modern Academic Editorial visual; not copied from AICIS/Multaqa, and not default AI Tailwind landing template.
- [ ] Brand, fonts, authentic photos, correct logo and hierarchy; no fabricated staff, speakers, venues, stats, journals, testimonials.
- [ ] Desktop and mobile intentionally composed; no carousel, no gratuitous animations/card spam.
- [ ] Published main event date 5 January 2027 and related day 6 January 2027 reflect Event Master; no invented August dates or hero “3–5 August”.
- [ ] Conference essentials, countdown, conference tracks, timeline, submission journey, publication, downloads, contact, FAQ, programs and approved partners are present in usable IA (not necessarily all on homepage).
- [ ] Abstract direct reviewer index; no AICIS-style Administrative Check in ICHES.
- [ ] CTA state correct for coming soon/open/closed; no pay-before-ACCEPT presenter CTA.

## Countdown gate — mandatory functionality

- [ ] Countdown exists on public site; four units D/H/M/S, visually integrated in an editable section.
- [ ] Time comes from **Admin-configurable timezone-aware event start** rather than hardcoded source or fake placeholder.
- [ ] Missing start time shows accurate notice/date, not fabricated hourly countdown.
- [ ] Future target yields nonzero correct values and ticks each second.
- [ ] Exact target time reaches zero; never negative; event-started message.
- [ ] Viewer timezone, server-supplied time, locale and SSR hydration do not cause incorrect values.
- [ ] Change target/timezone via Backoffice and publish → public shows new countdown without redeploy.
- [ ] Countdown keyboard, AR RTL, reduced motion, screen reader output tested.

## Website CMS gate — mandatory functionality

- [ ] Backoffice has Website CMS menu, Pages listing and create/edit page.
- [ ] Admin can add/reorder/hide/delete sections, choose supported layout variants, and set media/CTA/text.
- [ ] Admin can edit header/footer navigation, image media/alt/crop, speakers/partners where authorized, SEO and all supported locales.
- [ ] Changes saved as DRAFT are not live until published. Preview is private/non-indexed.
- [ ] Publishing generates revision history; rollback can restore content; cache is invalidated reliably.
- [ ] Countdown section and its presentation editable via CMS; target date/time bound to Event Master.
- [ ] New component **types** can be added to the registry without rewriting page code; content editors can reuse existing/new types without code.
- [ ] No raw arbitrary JS/HTML/CSS injection via CMS.
- [ ] Event dates, fees, tracks, CFP state and journal status come from master data and cannot be silently overridden by CMS.
- [ ] Content Editor's CMS rights never grant Finance, bank, academic decision or QR operations.

## Technical gate

- [ ] Existing stack and repository constraints audited; no claim deployment when app not built.
- [ ] TypeScript, lint/build, server and frontend tests run successfully (show actual commands/results).
- [ ] Playwright/Cypress or equivalent smoke at mobile (~375px), tablet (~768px), desktop (~1440px) when test environment exists.
- [ ] EN, ID and AR with genuine RTL layout; menus/dialogs and numeric IDs formatted correctly.
- [ ] Safe links, upload validation, proper media permissions, CSRF and scoped policies, data-minimized shared props.
- [ ] SEO metadata/title/description/OG/sitemap/canonical for published pages; SSR/fallback if supported.
- [ ] Assets optimized, meaningful alt text, keyboard controls, focus indicators, reduced motion and clear status messages.
- [ ] Photo/partner logo permission and footer info checked, no fake contact/phone/Bank data.
- [ ] Failed states, empty states, responsive navigation, countdown blank/zero and no-public-speakers fallbacks tested.
- [ ] Output shows screenshots/preview when possible, distinguishes PENDING tests from PASS, and lists any user preferences awaiting revision.

## Delivery gate

Show a cohesive completed frontend or prototype and CMS view as one *reviewable bundle*, not dozens of component-by-component requests. AI can make creative implementation decisions; user can amend hero, section order, photography or layout later. **No visual component becomes irrevocable** because the AI picked a default.
