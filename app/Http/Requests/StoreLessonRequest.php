<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
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
            'title'     => 'required|string|max:255',
            'content'   => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'order'     => 'required|integer|min:1',

            'files'     => 'nullable|array',
            'files.*'   => 'file|mimes:pdf,doc,docx,zip,jpg,png,mp4|max:51200',
        ];
    }


    public function messages(): array
    {
        return [
            'title.required'     => 'The lesson title is mandatory.',
            'title.string'       => 'The title must be a valid text string.',
            'title.max'          => 'The title may not be greater than 255 characters.',

            'content.string'     => 'The content must be a valid text.',

            'course_id.required' => 'You must select a course for this lesson.',
            'course_id.exists'   => 'The selected course does not exist in our records.',

            'order.required'     => 'The lesson order is required for organization.',
            'order.integer'      => 'The order must be a valid number.',
            'order.min'          => 'The order cannot be a negative value.',

            'files.array'   => 'The files must be sent as an array.',
            'files.*.file'  => 'One of the uploaded attachments is not a valid file.',
            'files.*.mimes' => 'Only PDF, DOC, ZIP, JPG, PNG, and MP4 files are allowed.',
            'files.*.max'   => 'Individual files cannot exceed 50MB.',
        ];
    }
}
