# Security Notes

Decisions here are kept separate from README.md so they stay traceable on their own — useful for citing during thesis defense if a security choice is questioned.

## CVE-2026-48019 — CRLF Injection in Laravel's Default `email` Validation Rule

**Status:** Accepted risk, with a manual mitigation applied.

**What it is:** A CRLF injection flaw in Laravel's built-in `email` validation rule which, combined with how Symfony Mailer/Mime handle certain character sequences, can let an attacker supply a crafted "email address" that manipulates outbound mail headers (message redirection, mail relay abuse). CVSS 8.9 (High).

**Affected versions (confirmed via `composer audit` against this project, cross-checked against the Packagist advisory `PKSA-mdq4-51ck-6kdq` and the CVE record directly):**
`>=9.0.0,<10.0.0` | `>=10.0.0,<11.0.0` | `>=11.0.0,<12.0.0` | `>=12.0.0,<12.60.0` | `>=13.0.0,<13.10.0`

This project installs `laravel/framework v11.55.0` — inside the affected `>=11.0.0,<12.0.0` range.

**Fixed in:** Laravel 12.60.0+ / 13.10.0+ only. **There is no patched release on the 11.x branch.** Staying on Laravel 11 means this specific CVE remains unpatched by upstream for as long as the project stays on 11.x.

**Why we're staying on Laravel 11 anyway:** This project deliberately targets Laravel 11 for consistency with NORMI's prior system (Northern Mindanao Colleges' mental health assessment system), which is also built on Laravel 11. Jumping to 12.x/13.x to dodge this CVE would break that intentional parity decision — a decision made with this tradeoff known, not overlooked.

**Mitigation applied:** `app/Rules/NoCrlfCharacters.php` — a custom validation rule that rejects any input containing a literal CR (`\r`), LF (`\n`), or their URL-encoded forms (`%0d`, `%0a`), case-insensitively. It must be composed alongside Laravel's built-in `email` rule (e.g. `['required', 'email', new NoCrlfCharacters]`) on every form field that accepts an email address — starting with the student self-registration form (Confirmed Product Decision #1) once it's built. This acts as an independent second layer, not reliant on Laravel's own `email` rule being fixed.

**Status update (Step 5, Auth backend wiring):** now wired in. `app/Http/Requests/Auth/RegisterRequest.php` composes it alongside `email` on the registration form's email field: `['required', 'string', 'email', 'max:255', 'unique:users,email', new NoCrlfCharacters]`. No longer sitting unused.

**Related advisories found in the same `composer audit` run, same installed version, also unpatched on 11.x:**
- `PKSA-3r5d-mb8f-1qw9` (High) — same CRLF-in-email-validation root cause, no CVE assigned yet.
- `PKSA-m5cs-t1y6-qpcs` (Medium) — temporary signed URL path confusion. Different root cause. No mitigation applied yet — this project doesn't currently use signed URLs, so exposure is low, but revisit if signed URLs get used later (e.g. for report-export links).

**Before the thesis defense:** re-run `composer audit` to check whether Laravel has backported a fix to the 11.x branch since this note was written.

## Dean Account Provisioning — Seeded, Not Self-Registered

**Status:** Intentional, by design — not an oversight.

Only Student Interns self-register (Confirmed Product Decision #1). There is no Dean self-registration flow and none is planned — the paper's scope names exactly two roles with the Dean approving Student Intern accounts, which implies the Dean role must already exist before any approval can happen. The first (and for now, only) Dean account is created directly via `database/seeders/DeanSeeder.php` (`dean@normi.edu.ph`, dev-only placeholder password), bypassing the registration -> pending -> approval pipeline entirely, since that pipeline doesn't apply to this role.

**Action item:** the seeded password is a local-dev placeholder and must be changed before any real deployment or live demo.
