<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
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
            'title'     => 'sometimes|string|max:255',
            'content'   => 'sometimes|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order'     => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];
    }


    public function messages(): array
    {
        return [
            'title.string'    => 'The title must be a valid text string.',
            'title.max'       => 'The title may not be greater than 255 characters.',

            'content.string'  => 'The content must be a valid text string.',

            'image.image'     => 'The uploaded file must be an image.',
            'image.mimes'     => 'Only jpeg, png, jpg, and webp formats are accepted.',
            'image.max'       => 'The image size should not exceed 2MB.',

            'order.integer'   => 'The display order must be a whole number.',
            'is_active.boolean' => 'The active status must be either true or false.',
        ];
    }
}
