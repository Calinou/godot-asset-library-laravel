<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Asset;
use App\AssetPreview;
use Illuminate\Validation\Rule;
use App\Rules\SuccessRespondingUrl;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAsset extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // We use Laravel gates for authorization, no need to duplicate code here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array[]|string[]
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'blurb' => 'nullable|string|max:60',
            'description' => 'required|string|max:10000',
            'tags' => [
                'nullable',
                'string',
                // Allow alphanumeric characters, numbers, whitespace and dashes in the tag list string.
                // Uppercase characters will become lowercase when persisted to the database.
                'regex:/^[A-Za-z0-9,\s-]+$/',
                function ($attribute, $value, $fail) {
                    $tags = explode(',', $value);

                    if (count($tags) > Asset::MAX_TAGS) {
                        $fail(
                            __('An asset may have :maxTags tags at most, but you specified :numTags tags.',
                                ['maxTags' => Asset::MAX_TAGS, 'numTags' => count($tags)])
                        );
                    }
                },
            ],
            'category_id' => 'required|integer|gte:0|lt:'.Asset::CATEGORY_MAX,
            'cost' => ['required', Rule::in(array_keys(Asset::LICENSES))],
            'browse_url' => [
                'required',
                'bail',
                'url',
                'max:2000',
                // Only allow well-formed GitHub, GitLab.com and Bitbucket repository URLs
                'regex:/\/\/(github\.com|gitlab\.com|bitbucket\.org).+\/.+/',
                new SuccessRespondingUrl(),
            ],
            'issues_url' => [
                'nullable',
                'bail',
                'url',
                'max:2000',
                new SuccessRespondingUrl(),
            ],
            'icon_url' => [
                'nullable',
                'bail',
                'url',
                'max:2000',
                'ends_with:.png,.PNG,jpg,JPG,jpeg,JPEG',
                new SuccessRespondingUrl(),
            ],

            // An asset must have at least one version registered
            'versions' => 'required|array|min:1',
            'versions.*.id' => 'nullable|integer|gte:1',
            'versions.*.version_string' => 'required|string|max:50',
            'versions.*.godot_version' => ['required', Rule::in(Asset::GODOT_VERSIONS)],
            'versions.*.download_url' => [
                'nullable',
                'bail',
                'url',
                'max:2000',
                'ends_with:.zip,.ZIP',
                // Don't allow manually linking to a moving branch (typically `master`).
                // This can't detect all branches in the repository, it's just here as a basic deterrent.
                'not_regex:/master\.zip/',
                new SuccessRespondingUrl(),
            ],

            // Asset previews are optional, though (even if recommended)
            'previews' => 'nullable|array|max:4',
            'previews.*.id' => 'nullable|integer|gte:1',
            'previews.*.type_id' => 'required|integer|gte:0|lt:'.AssetPreview::TYPE_MAX,
            'previews.*.link' => [
                'required',
                'bail',
                'url',
                'max:2000',
                new SuccessRespondingUrl(),
            ],
            'previews.*.thumbnail' => [
                'nullable',
                'bail',
                'url',
                'max:2000',
                'ends_with:.png,.PNG,jpg,JPG,jpeg,JPEG',
                new SuccessRespondingUrl(),
            ],
            'previews.*.caption' => 'nullable|string|max:60',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'browse_url' => __('repository URL'),
            'issues_url' => __('issue reporting URL'),
            'icon_url' => __('icon URL'),
            'versions.*.download_url' => __('download URL'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'browse_url.regex' => __('The :attribute must point to a public GitHub, GitLab.com or Bitbucket repository.'),
            'tags.regex' => __('Tags can only contain lowercase characters, numbers and dashes, and must be separated with commas (,).'),
        ];
    }
}
