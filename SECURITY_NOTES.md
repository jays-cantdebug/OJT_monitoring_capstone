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
