# ICH-S0-06 REV-01 - minimal Inertia locale correction

Owner GO CODING REV-01, 29 October 2026, supersedes the work order's historical PENDING GO status. Verified DESKTOP-PE5D86C, repository `D:/PINJAM-AFNAN/Herd/iches-unisya`, branch `feat/ich-s0-06-backoffice-shell`, clean baseline/direct parent `f3df8c59c7b76df3b2c7bec56b16d91e4da3f7f5`.

## Change and regression

One production line added in `resources/js/app.ts`: Inertia `success` calls existing `syncLocale()` using the response page locale. Initial setup sync and `navigate` listener remain unchanged. Same-URL responses replace history and do not emit navigate in the installed Inertia version; success is emitted. No backend, drawer, role, schema, dependency or configuration edits.

Durable opt-in regression: `locale-browser-regression.cjs` uses native Node WebSocket plus installed local Chrome, without new dependencies. It launches a hidden isolated headless browser with real device metrics, uses the local demo presenter and real HTTP/session/CSRF/Inertia flow, then closes Chrome and deletes only its unique temporary profile. UI form changes/buttons are driven through DOM events/clicks. It does not claim native physical keyboard validation.

Executed RED before changing app.ts: the browser regression exited 1 at the first dashboard EN -> ID assertion: `Same-URL success must synchronize html lang`, actual en, expected id. After the one-line source change and build, initial browser run passed 93 counted checks. Final strengthened regression passed 95 counted checks (exit 0); guards/additional assertions are not included in that counter.

Final coverage:
- Authenticated `/dashboard` EN -> ID -> AR -> EN with translated heading and shell, correct HTML lang/dir, same document sentinel, zero Document requests, exactly one POST /locale, one 302 and one JSON 200 per change. No forced reload or extra visit.
- AR session locale/RTL persists after explicit reload. Viewports 390/768/1440 all have actual viewport width equal to emulated width and no horizontal overflow.
- AR mobile drawer opens on right, focus enters dialog, close button works and restores trigger focus. Native CDP keyboard delivery remains outside this regression and no keyboard fix is claimed.
- Real Inertia visit from dashboard to homepage, back, same-URL locale change, forward and back: distinct AR/EN saved history locales restore correctly. This exercises the retained navigate handler, beyond initial setup.
- Existing workspace grant selected via UI; completed response and GET /me/workspaces prove server session selection persisted; same presenter identity retained. No grants/users/events created or changed.
- Logout works; guest `/login` EN -> ID -> AR -> EN works without reload. Subsequent `/dashboard` redirects to login. No uncaught browser exceptions.

## Serial commands and actual results

| Command | Exit | Result |
| --- | --- | --- |
| Browser script before fix/build, with ICHES_BROWSER_QA=1 | 1 | Expected RED, en != id |
| `php artisan test --compact --no-ansi` | 0 | 30 PASS / 684 assertions; includes existing auth/workspace/locale/CSRF negative cases |
| `composer validate --strict` | 0 | PASS |
| Task PHP Pint | N/A | No PHP changed |
| `npm.cmd run typecheck` | 0 | PASS |
| `npm.cmd run build` | 0 | PASS, Vite 7.3.7 |
| `git diff --check` | 0 | PASS |
| Final browser regression with ICHES_BROWSER_QA=1 | 0 | PASS, 95 counted checks |
| Final staged `git diff --cached --check` | 0 | PASS |

Reproduce from this repository in PowerShell:

```powershell
$env:ICHES_BROWSER_QA='1'
try { node harness/evidence/ICH-S0-06/locale-browser-regression.cjs; $qaExit=$LASTEXITCODE }
finally { Remove-Item Env:ICHES_BROWSER_QA }
exit $qaExit
```

Requires local Herd application, installed Chrome, existing demo presenter grant and built assets. Opt-in is process-only. Default output is `%TEMP%/ICHES_S006_REV01_QA`. Script refuses another computer/repository. Do not run alongside another browser/storage-fake test process.

Committed sanitized evidence: `rev01-browser-results.json`, `rev01-ar-390.png` (before reload), `rev01-ar-1440.png`, `rev01-ar-drawer.png`. JSON contains DOM state and status/timing summaries, no cookies, tokens, passwords or raw network payloads. Screenshots show only synthetic demo identity.

Cleanup: both red and green browsers closed; unique temporary profiles removed (independent directory check found none). Final run logged out and verified guest protection. No persistent test fixtures, migration commands, reset, seeding, role changes, .env changes, other-project work or publication actions. Generated Vite assets remain ignored local build output. No MySQL test opt-in or seeder was run. TypeScript/compiler verifies success event typing; Windows native-keyboard delivery and Linux CI are not claimed. No remaining blocker for this bounded correction; independent review/owner acceptance remains separate.

Exactly one additional local commit; stop before push, PR, merge or deployment. Prior original task evidence is preserved historically.
