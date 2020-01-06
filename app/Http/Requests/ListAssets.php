<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Asset;
use App\AssetVersion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListAssets extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'page' => 'nullable|integer|gte:1',
            'max_results' => 'nullable|integer|gte:1|lte:500',
            'user' => 'nullable|string',
            'type' => 'nullable|string|in:any,addon,project',
            'category' => 'nullable|integer|gte:0|lt:'.Asset::CATEGORY_MAX,
            'godot_version' => ['nullable', 'string', Rule::in(AssetVersion::GODOT_VERSION_FILTERS)],
            'reverse' => 'nullable|string',
            'sort' => 'nullable|string',
            'filter' => 'nullable|string',
        ];
    }
}
