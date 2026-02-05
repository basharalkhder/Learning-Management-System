<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
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
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('reviews')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ],
            'rating'    => 'required|integer|min:1|max:5',
            'comment'   => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.unique' => 'You have already submitted a review for this course.',
            'course_id.required' => 'The course ID is required.',
            'course_id.exists'   => 'The selected course does not exist.',

            'rating.required'    => 'Please provide a rating.',
            'rating.integer'     => 'The rating must be a number.',
            'rating.min'         => 'The rating must be at least 1 star.',
            'rating.max'         => 'The rating cannot be more than 5 stars.',

            'comment.string'     => 'The comment must be a valid text.',
            'comment.max'        => 'The comment may not be greater than 1000 characters.',
        ];
    }
}
