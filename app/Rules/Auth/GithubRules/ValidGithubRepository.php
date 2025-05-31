<?php

namespace App\Rules\Auth\GithubRules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidGithubRepository implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (! preg_match('/^https:\/\/github\.com\/[A-Za-z0-9_-]+\/[A-Za-z0-9._-]+$/', $value)) {
            $fail('gitHub_url must be a valid GitHub repository like https://github.com/ABDALRZAQ345/Lms');
        } else {

            try {
                $response = \Http::get($value);
                if ($response->status() !== 200) {
                    $fail('The GitHub repository does not exist or is not publicly accessible or there is an error from our side or github side.');
                }
            } catch (\Exception $exception) {
                $fail('some thing went wrong while trying to connect to github.');
            }

        }

    }
}
