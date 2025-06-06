<?php

namespace App\Rules\Auth\GithubRules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidGitHubAccount implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^https:\/\/github\.com\/[A-Za-z0-9_-]+$/', $value)) {
            $fail('gitHub_account must be a valid GitHub account like https://github.com/ABDALRZAQ345');
        }

    }
}
