<?php

namespace App\Http\Requests;

use App\Asset;
use App\AssetPreview;
use Illuminate\Validation\Rule;
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
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'blurb' => 'nullable|string|max:60',
            'description' => 'required|string|max:10000',
            'tags' => 'nullable|string|max:10000',
            'category_id' => 'required|integer|gte:0|lt:'.Asset::CATEGORY_MAX,
            'cost' => ['required', Rule::in(array_keys(Asset::LICENSES))],
            'browse_url' => 'required|url|max:2000',
            'issues_url' => 'nullable|url|max:2000',
            'icon_url' => 'nullable|url|max:2000',

            // An asset must have at least one version registered
            'versions' => 'required|array|min:1',
            'versions.*.version_string' => 'required|string|max:50',
            'versions.*.godot_version' => ['required', Rule::in(Asset::GODOT_VERSIONS)],
            'versions.*.download_url' => 'nullable|url',

            // Asset previews are optional, though (even if recommended)
            'previews' => 'nullable|array',
            'previews.*.type_id' => 'required|integer|gte:0|lt:'.AssetPreview::TYPE_MAX,
            'previews.*.link' => 'required|url|max:2000',
            'previews.*.download_url' => 'nullable|url|max:2000',
        ];
    }
}
