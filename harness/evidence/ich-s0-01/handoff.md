# ICH-S0-01 implementation evidence — 28 October 2026

Owner authorization: explicit GO CODING ICH-S0-01 in this session, bounded by 21B/21D.

- Remote: https://github.com/akhmadafnan/iches-unisya.git
- Branch: `feat/ich-s0-01-foundation`
- Exact clean main branch point: `4137dce48e85fab5c25c89b7f3a1fde1d0fa3b89`
- Governance ancestor: `275cfbfd0e431033eb05d2c519eda19282465962` (verified).
- Existing Herd Free link preserved: http://iches-unisya.test/ → this repository, PHP 8.4.
- Runtime: PHP 8.4.24, Composer 2.10.1, Node 26.4.0, npm 11.17.0.

## Result

Minimal Laravel → Inertia → Vue Composition API page runs through Herd with built Vite assets. Local session/cache use files; queue is synchronous. Database connections are intentionally empty. No database, migration, seeder, authentication, CMS, countdown or other business module was implemented or executed. No source was copied from the legacy app; the Laravel 13.0.0 skeleton was staged outside the checkout with install/scripts disabled, then selected application files were copied. Governance files were preserved.

Local `.env` was created from the sanitized example and a local app key generated; `.env`, vendor, node_modules, generated assets, sessions and logs are ignored. No credentials are recorded in this evidence.

## Locked direct versions

| Package | Installed |
|---|---|
| laravel/framework | 13.39.0 |
| inertiajs/inertia-laravel | 2.0.28 |
| @inertiajs/vue3 | 2.3.29 |
| vue | 3.5.44 |
| typescript | 5.9.3 |
| tailwindcss / @tailwindcss/vite | 4.3.3 |
| vite | 7.3.7 |
| laravel-vite-plugin | 2.1.0 |
| @vitejs/plugin-vue | 6.0.10 |
| vue-tsc | 3.3.13 |
| @types/node | 26.6.5 |
| phpunit/phpunit | 12.5.42 |
| laravel/pint | 1.32.1 |
| mockery/mockery | 1.6.15 |
| nunomaduro/collision | 8.9.5 |

## Commands actually executed

| Command | Exit | Actual result |
|---|---:|---|
| `git fetch origin` / `git merge --ff-only origin/main` | 0 / 0 | Main already current, initially clean |
| `git merge-base --is-ancestor 275cfbfd0e431033eb05d2c519eda19282465962 main` | 0 | Approved governance ancestry |
| `git switch -c feat/ich-s0-01-foundation` | 0 | Feature branch created |
| `composer install --no-interaction --prefer-dist` | 0 | Installed; repeated from lock successfully |
| `composer validate --strict` | 0 | Valid manifest and lock |
| `composer check-platform-reqs` | 0 | PHP and extensions compatible |
| `php artisan key:generate --no-interaction` | 0 | Ignored local env only |
| `php artisan --version` | 0 | Laravel Framework 13.39.0 |
| `php artisan route:list` | 0 | GET/HEAD `/` and `/up`; no auth routes |
| `php artisan test --filter=FoundationSmokeTest` | 0 final | 3 tests, 22 assertions: HTML/Inertia page, JSON navigation, unknown route 404; DB connection prohibited by mock |
| `php vendor/bin/pint --test` | 0 final | Formatting passes |
| `npm.cmd install` | 0 | Installed and generated lock; audit reported zero vulnerabilities |
| `npm.cmd ci` | 0 | Reinstalled from lock; audit reported zero vulnerabilities |
| `npm.cmd run build` | 0 | Vite production assets built, including after npm ci |
| `npm.cmd run typecheck` | 0 | vue-tsc passed, including after npm ci |
| `npm.cmd ls --depth=0` | 0 | Direct versions recorded above |
| `curl.exe -sS -o NUL -w "%{http_code}" http://iches-unisya.test/` | 0 | 404 before implementation; 200 after implementation |
| `curl.exe -sS -o NUL -w "%{http_code}" http://iches-unisya.test/foundation-route-that-does-not-exist` | 0 | HTTP 404 |
| `node harness/evidence/ich-s0-01/browser-smoke.mjs` | 0 | Chrome 156.0.8078.48, desktop 1440×900 and mobile 390×844; Vue mounted, Tailwind loaded, correct title/task, no overflow or runtime/console/network/HTTP errors |
| `git diff --cached --check` | 0 | No whitespace errors |
| `git diff --exit-code -- README.md AGENTS.md PLANS.md WORKSPACE.md docs .agents` | 0 | Protected governance files unchanged |

Initial Pint check exited 1 for import ordering/line endings; `php vendor/bin/pint` exited 0 and fixed these, then the check passed. A later smoke run with an existing Vite manifest found the navigation fixture omitted `X-Inertia-Version`, yielding the expected protocol HTTP 409. The test now sends the current manifest version and passes with built assets present. The failed run is retained in `verification-before-test-fix.json`; final command results are in `verification.json`.

npm emitted an esbuild postinstall approval warning. No global/script approval settings were changed; the actual install, clean reinstall, build and typecheck all succeeded.

## Review artifacts and limits

- `changed-files.txt`: complete staged application/evidence file inventory, including the appended build log.
- `desktop.png`, `mobile.png`: actual Herd screenshots, visually inspected.
- `browser-smoke.mjs`: repeatable local Chrome check using Node built-ins and an isolated temporary browser profile; default Chrome path can be overridden via `CHROME_PATH`.
- Final staged scope/secret-pattern scan found no protected documents, `.env`, database schema, dependency trees, compiled assets, app keys or credential patterns in the commit candidate.
- Fresh checkout: `composer install`, copy `.env.example` to ignored `.env`, `php artisan key:generate`, `npm.cmd ci`, `npm.cmd run build`. Use the existing Herd link; no artisan server or database setup is needed.
- No implementation blocker remains. Owner acceptance, ChatGPT review and any merge remain separate. No push, PR, merge or deployment was performed in this task.
- Documentation status snapshots remain unchanged for ChatGPT to reconcile after review. No full website, localization/Arabic UAT, database UAT or production readiness is claimed.
