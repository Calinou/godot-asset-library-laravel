<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

/**
 * Only accept publicly accessible repository URLs hosted on GitHub, GitLab.com or Bitbucket.
 * This doesn't perform URL checks; use the SuccessRespondingUrl rule for that.
 */
class GitRepositoryUrl implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return
            Str::contains($value, '//github.com/') ||
            Str::contains($value, '//gitlab.com/') ||
            Str::contains($value, '//bitbucket.org/');
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        return __('The :attribute must point to a public GitHub, GitLab.com or Bitbucket repository.');
    }
}
