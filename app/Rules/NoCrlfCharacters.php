<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Manual mitigation for CVE-2026-48019 (CRLF injection in Laravel's default
 * `email` rule, unpatched on the 11.x branch). See SECURITY_NOTES.md.
 */
class NoCrlfCharacters implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && preg_match('/\r|\n|%0d|%0a/i', $value)) {
            $fail('The :attribute field contains invalid characters.');
        }
    }
}
