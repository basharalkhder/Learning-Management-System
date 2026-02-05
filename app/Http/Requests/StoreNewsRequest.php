<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
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
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'order'   => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];
    }


    public function messages(): array
{
    return [
        'title.required' => 'The news title is mandatory.',
        'title.string'   => 'The title must be a valid string.',
        'title.max'      => 'The title may not be greater than 255 characters.',

        'content.required' => 'Please provide the news content.',

        'image.image'    => 'The uploaded file must be an image.',
        'image.mimes'    => 'Supported image formats are: jpeg, png, jpg, and webp.',
        'image.max'      => 'The image size should not exceed 2MB.',

        'order.integer'  => 'The display order must be a valid number.',
        'is_active.boolean' => 'The active status must be true or false.',
    ];
}
}
