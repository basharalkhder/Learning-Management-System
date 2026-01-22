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



        return [
            'title'         => 'sometimes|required|string|min:5|max:255',
            'description'   => 'sometimes|required|string|min:20',
            'type'        => 'sometimes|required|in:online,onsite',
            'capacity'    => [
                'sometimes',
                'required_if:type,onsite',
                'prohibited_if:type,online',
                'nullable',
                'integer',
                'min:1'
            ],
            'price'         => 'sometimes|required|numeric|min:0',

            'registration_deadline' => 'sometimes|required|date|after:now',
            

            'images'      => 'sometimes|nullable|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
            
            'pdfs'        => 'sometimes|nullable|array',
            'pdfs.*'      => 'file|mimes:pdf|max:10240',
        
        ];
    }
    public function messages(): array
    {
        return [
            'price.numeric'          => 'The price must be a valid number.',
            
            'type.in'                => 'The course type must be either online or onsite.',

            'capacity.required_if'   => 'The capacity field is required when the course type is onsite.',
            'capacity.prohibited_if' => 'The capacity field must be empty when the course type is online.',
            'capacity.integer'       => 'The capacity must be a whole number.',

            
            'images.*.image'         => 'One or more files are not valid images.',
            'images.*.mimes'         => 'Only JPEG, PNG, and JPG are allowed.',
            'pdfs.*.mimes'           => 'Only PDF files are allowed.',
        ];
    }
}
