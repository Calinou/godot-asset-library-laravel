<?php

namespace App\Http\Requests;

use App\Asset;
use App\AssetPreview;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAsset extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // The user must be logged in to submit an asset
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'blurb' => 'nullable|string|max:50',
            'description' => 'required|string|max:10000',
            'category_id' => 'required|integer|gte:0|lt:'.Asset::CATEGORY_MAX,
            'cost' => [
                'required',
                Rule::in(array_keys(Asset::LICENSES)),
            ],
            'browse_url' => 'required|url',
            'issues_url' => 'nullable|url',
            'icon_url' => 'nullable|url',

            // An asset must have at least one version registered
            'versions' => 'required|array|min:1',
            'versions.*.version_string' => 'required|string',
            'versions.*.godot_version' => [
                'required',
                Rule::in(Asset::GODOT_VERSIONS),
            ],
            'versions.*.download_url' => 'nullable|url',

            // Asset previews are optional, though (even if recommended)
            'previews' => 'nullable|array',
            'previews.*.type_id' => 'required|integer|gte:0|lt:'.AssetPreview::TYPE_MAX,
            'previews.*.link' => 'required|url',
            'previews.*.download_url' => 'nullable|url',
        ];
    }
}
