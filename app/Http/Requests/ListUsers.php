<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListUsers extends FormRequest
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
        ];
    }
}
