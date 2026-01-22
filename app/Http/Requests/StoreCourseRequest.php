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
            'title'         => 'required|string|min:5|max:255|',
            'type'     => 'required|in:online,onsite',
            'capacity' => [
                'required_if:type,onsite',
                'prohibited_if:type,online',
                'nullable',
                'integer',
                'min:1'
            ],
            'description'   => 'required|string|min:20',
            'price'         => 'required|numeric|min:0',
            'registration_deadline' => 'required|date|after:now',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',


            'pdfs' => 'nullable|array',
            'pdfs.*' => 'file|mimes:pdf|max:10240',

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

            'type.required'  => 'Please specify the course type (online or onsite).',
            'type.in'        => 'The course type must be either online or onsite.',

            'capacity.required_if' => 'The capacity field is required when the course type is onsite.',
            'capacity.prohibited_if' => 'The capacity field must be empty when the course type is online.',
            'capacity.integer'     => 'The capacity must be a whole number.',
            'capacity.min'         => 'The capacity must be at least 1 seat.',

            'price.required'         => 'Course price must be specified.',
            'price.numeric'          => 'The price must be a valid number.',
            'price.min'              => 'Price cannot be a negative value.',

            'registration_deadline.required' => 'The registration deadline date is required.',
            'registration_deadline.date'     => 'Please enter a valid date format.',
            'registration_deadline.after'    => 'The deadline must be a future date and time.',


            'images.array' => 'Images must be uploaded as a list.',
            'images.*.image' => 'One or more files in the images field are not valid images.',
            'images.*.mimes' => 'Only JPEG, PNG, and JPG formats are allowed for images.',
            'images.*.max' => 'Each image must not exceed 2MB.',


            'pdfs.array' => 'PDFs must be uploaded as a list.',
            'pdfs.*.file' => 'One or more items in the PDFs field are not valid files.',
            'pdfs.*.mimes' => 'The files must be in PDF format only.',
            'pdfs.*.max' => 'Each PDF file must not exceed 10MB.',

        ];
    }
}
