<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating'  => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.integer' => 'The rating must be a whole number.',
            'rating.min'     => 'The rating must be at least 1 star.',
            'rating.max'     => 'The rating cannot exceed 5 stars.',
        ];
    }
}
