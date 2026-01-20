<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'title'         => 'required|string|min:5|max:255|unique:courses,title',
            'description'   => 'required|string|min:20',
            'price'         => 'required|numeric|min:0',
            
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'This course title already exists. Please choose a different name.',
            'title.required'         => 'The course title is mandatory.',
            'title.min'              => 'The title must be at least 5 characters long.',
            'description.required'   => 'Please provide a detailed description.',
            'description.min'        => 'Description should be at least 20 characters.',
            'price.required'         => 'Course price must be specified.',
            'price.numeric'          => 'The price must be a valid number.',
            'price.min'              => 'Price cannot be a negative value.',

        ];
    }
}
