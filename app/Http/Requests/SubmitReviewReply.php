<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitReviewReply extends FormRequest
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
            'comment' => 'nullable|string|max:2000',
        ];
    }
}
