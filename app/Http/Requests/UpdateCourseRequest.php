<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
       
        $courseId = $this->route('course');

        return [
            'title'         => 'sometimes|required|string|min:5|max:255|unique:courses,title,' . $courseId,
            'description'   => 'sometimes|required|string|min:20',
            'price'         => 'sometimes|required|numeric|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            
            'title.unique' => 'This course title is already taken by another course.',
            'price.numeric' => 'The price must be a valid number.',
        ];
    }
}
