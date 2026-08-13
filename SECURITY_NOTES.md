# Security Notes

Decisions here are kept separate from README.md so they stay traceable on their own — useful for citing during thesis defense if a security choice is questioned.

## CORRECTION (2026-08-09) — Self-Registration Was Wrong Against the Paper

**Status:** Caught and confirmed against the paper. Rework in progress, not yet applied to code as of this note.

**What was wrong:** "Confirmed Product Decision #1" (students self-register; account requires Dean approval before use) was built and documented below as if it were paper-derived. It was not verified against the paper at the time — it was accepted as a stated product decision without that cross-check. Re-verification against the paper now confirms: **the Dean creates Student Intern accounts directly.** There is no student self-registration flow and no approval step, matching NORMI's own prior-system pattern (admin-provisioned accounts) rather than a public sign-up-then-approve pipeline.

**What this supersedes:** every reference to "Confirmed Product Decision #1" below, and the self-registration flow it describes (`auth.register`, the `pending`/`approved`/`rejected` user-status pipeline, `dean.pending-approvals`), is retained here for audit-trail purposes but is **no longer the intended design.** Do not build against it. See the corresponding investigation/proposal delivered in-session for the full list of what's being removed vs. repurposed.

**Why flagged this way, not silently fixed:** per this project's working agreement, paper/system drift gets flagged the moment it's caught, not reconciled quietly — same discipline that caught NORMI's own drift issues late, which this project is explicitly trying to avoid repeating.

---

## CVE-2026-48019 — CRLF Injection in Laravel's Default `email` Validation Rule

**Status:** Accepted risk, with a manual mitigation applied.

**What it is:** A CRLF injection flaw in Laravel's built-in `email` validation rule which, combined with how Symfony Mailer/Mime handle certain character sequences, can let an attacker supply a crafted "email address" that manipulates outbound mail headers (message redirection, mail relay abuse). CVSS 8.9 (High).

**Affected versions (confirmed via `composer audit` against this project, cross-checked against the Packagist advisory `PKSA-mdq4-51ck-6kdq` and the CVE record directly):**
`>=9.0.0,<10.0.0` | `>=10.0.0,<11.0.0` | `>=11.0.0,<12.0.0` | `>=12.0.0,<12.60.0` | `>=13.0.0,<13.10.0`

This project installs `laravel/framework v11.55.0` — inside the affected `>=11.0.0,<12.0.0` range.

**Fixed in:** Laravel 12.60.0+ / 13.10.0+ only. **There is no patched release on the 11.x branch.** Staying on Laravel 11 means this specific CVE remains unpatched by upstream for as long as the project stays on 11.x.

**Why we're staying on Laravel 11 anyway:** This project deliberately targets Laravel 11 for consistency with NORMI's prior system (Northern Mindanao Colleges' mental health assessment system), which is also built on Laravel 11. Jumping to 12.x/13.x to dodge this CVE would break that intentional parity decision — a decision made with this tradeoff known, not overlooked.

**Mitigation applied:** `app/Rules/NoCrlfCharacters.php` — a custom validation rule that rejects any input containing a literal CR (`\r`), LF (`\n`), or their URL-encoded forms (`%0d`, `%0a`), case-insensitively. It must be composed alongside Laravel's built-in `email` rule (e.g. `['required', 'email', new NoCrlfCharacters]`) on every form field that accepts an email address. This acts as an independent second layer, not reliant on Laravel's own `email` rule being fixed.

**Status update (Step 5, Auth backend wiring):** wired in on `app/Http/Requests/Auth/RegisterRequest.php` (the student self-registration form's email field). **Per the 2026-08-09 correction above, that form is being removed** — this rule must be carried over onto whatever form request replaces it for the Dean's "Create Student Intern Account" flow. Don't let this mitigation get silently dropped during the rework.

**Related advisories found in the same `composer audit` run, same installed version, also unpatched on 11.x:**
- `PKSA-3r5d-mb8f-1qw9` (High) — same CRLF-in-email-validation root cause, no CVE assigned yet.
- `PKSA-m5cs-t1y6-qpcs` (Medium) — temporary signed URL path confusion. Different root cause. No mitigation applied yet — this project doesn't currently use signed URLs, so exposure is low, but revisit if signed URLs get used later (e.g. for report-export links).

**Before the thesis defense:** re-run `composer audit` to check whether Laravel has backported a fix to the 11.x branch since this note was written.

## Dean Account Provisioning — Seeded, Not Self-Registered

**Status:** Intentional, by design — not an oversight. Still accurate after the 2026-08-09 correction above; this note only ever described the Dean role.

There is no Dean self-registration flow and none is planned. The first (and for now, only) Dean account is created directly via `database/seeders/DeanSeeder.php` (`dean@normi.edu.ph`, dev-only placeholder password). **Per the 2026-08-09 correction above, Student Intern accounts now follow the same admin-provisioned pattern** — created by the Dean directly, not via self-registration — so this seeded/provisioned approach turns out to be the norm for both roles, not an exception unique to Dean.

**Action item:** the seeded password is a local-dev placeholder and must be changed before any real deployment or live demo.

## Live Map — Leaflet.js + OpenStreetMap Instead of Google Maps

**Status:** Intentional deviation from the paper, [USER]-confirmed — not an oversight.

**What the paper says:** [PAPER, Ch3 3.5] specifies the Dean's Live Map feature is to be built on the Google Maps API.

**What was actually built:** Leaflet.js rendering OpenStreetMap tiles, wired to the existing real-time GPS ping pipeline (`GpsPingBroadcast` over the `dean.live-map` Reverb channel, consumed by the `liveMap` Alpine component in `resources/js/app.js`).

**Why:** Google Maps API requires a billing-enabled Google Cloud project (a credit card on file) even to stay within the free tier, plus an API key that has to be provisioned, restricted, and kept out of source control. Leaflet + OpenStreetMap needs neither — no API key, no credit card, no billing account — while providing the same core capability this feature needs (tile map + markers). For a thesis project with no institutional billing account behind it, this removes a hard external dependency for zero functional loss on the requirements actually being demonstrated.

**Scope of the deviation:** front-end mapping library only. The GPS ping capture, storage (`gps_pings` table), and real-time broadcast layer are unaffected and were already built/verified independently of which map renderer displays them — see `app/Events/GpsPingBroadcast.php` and `app/Http/Controllers/Dean/LiveMapController.php`.

**Trade-off accepted:** OpenStreetMap's public tile servers are a free shared resource with fair-use rate limits (not meant for high-traffic production use without a paid tile provider or self-hosting). Acceptable for this project's scale (thesis demo, single institution, small concurrent user count). Revisit if this ever needs to scale beyond that.

## Student Account Credential Delivery — Real Email Sending Explored, Not Implemented

**Status:** Investigated, blocked on external prerequisites the project doesn't currently have. Reverted to the existing on-screen display as the only delivery mechanism — no code changed.

**What was requested:** when a Dean creates a Student Intern account, email the generated temporary password directly to the student's email address, in addition to (or instead of) the existing one-time on-screen display in `Dean\StudentAccountController::store`.

**What was tried, and why each was blocked:**
- **Gmail SMTP** — requires a Google Account App Password, which needs 2-Step Verification enabled on that account. Not available for the account being used.
- **Mailtrap** — the free/demo sending setup (no custom domain) only delivers to the Mailtrap account owner's own inbox for testing purposes; it cannot deliver to arbitrary real recipients (i.e., actual students). Real delivery via Mailtrap requires verifying a sending domain, which requires DNS record access the project doesn't currently have.

**Current state:** unchanged from before this investigation. `.env` still has `MAIL_MAILER=log` (the Laravel default placeholder config — outgoing mail is written to `storage/logs/laravel.log`, never actually sent). No `App\Mail` classes exist. The Dean-facing "show once" credential banner in `dean.students` (populated by `StudentAccountController::store`'s `created` view data) remains the sole way credentials reach the Dean, who is responsible for manually relaying them to the student. This was already working before this investigation started and was not touched.

**Revisit when:** the institution provisions either (a) a real email account with 2-Step Verification enabled (for Gmail App Passwords), or (b) a verified sending domain with DNS access (for Mailtrap or any other transactional mail provider). At that point, wiring it up is mechanical — `config/mail.php`'s `smtp` mailer is already fully scaffolded and reads from standard `MAIL_*` env vars; only a new Mailable class + Markdown email view + one call in `StudentAccountController::store` would be needed.

## Reverb WebSocket Server — Must Be Run as a Persistent Process

**Status:** Operational requirement, not a code defect. Confirmed by direct investigation (2026-08-13) after the Dean's Live Map showed no on-duty students despite students having successfully timed in.

**What was found:** Reverb (`php artisan reverb:start`, the WebSocket server behind `BROADCAST_CONNECTION=reverb`) was not running at all — nothing was listening on port 8080 (`netstat`), and the only live PHP process was the plain `php -S` dev server on a different port. `storage/logs/laravel.log` shows two distinct symptoms of this over time: every GPS ping broadcast attempt failing with `cURL error 7: Failed to connect to localhost port 8080`, and a separate crash from `reverb:start` itself on 2026-08-12 because MySQL was down at the moment it tried to boot (it reads a `laravel:reverb:restart` cache key from the DB before starting, so a DB outage takes Reverb down with it).

**This is expected to happen whenever Reverb isn't explicitly started.** Unlike the built-in `php artisan serve` / Herd dev server, Reverb does not start itself — it must be run as its own long-lived process (e.g. `php artisan reverb:start`, kept alive via a process manager or supervisor in any real deployment) alongside the web server and MySQL, for as long as the Live Map feature needs to receive real-time updates.

**Confirmed: the rest of the app degrades gracefully when Reverb is down.** This was verified directly, not assumed:
- `GpsPingController::store()` (`app/Http/Controllers/Student/GpsPingController.php`) saves the `GpsPing` row to the database *before* attempting to broadcast it, and wraps the `broadcast()` call in a `try`/`catch (BroadcastException $e)` that only logs a warning. A ping is never lost or rejected because Reverb is unreachable — only the live push to the Dean's map is skipped.
- Time In/Out (`DtrController`) does not touch broadcasting at all and is entirely unaffected by Reverb's state.
- The only user-visible effect of Reverb being down is that the Dean's Live Map won't show real-time marker updates — historical DTR/report data, attendance, and all other features are unaffected.

**Action item:** before any demo or deployment where the Live Map needs to work live, confirm Reverb is running (`netstat`/process check for port 8080, or watch for the "GPS ping broadcast failed" warning in `storage/logs/laravel.log` as a sign it isn't). Start it with `php artisan reverb:start`. For anything beyond local dev, run it under a process supervisor (e.g. Windows Task Scheduler / NSSM, or `systemd`/`supervisord` on Linux) so it restarts automatically on crash or reboot — a plain foreground `reverb:start` will not survive a terminal closing or the machine restarting.
