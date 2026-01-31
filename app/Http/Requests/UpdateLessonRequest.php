<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
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

            'title'     => 'sometimes|required|string|max:255',
            'content'   => 'sometimes|nullable|string',
            'course_id' => 'sometimes|required|exists:courses,id',
            'order'     => 'sometimes|required|integer|min:1',

            'files'     => 'sometimes|nullable|array',
            'files.*'   => 'file|mimes:pdf,doc,docx,zip,jpg,png,mp4|max:51200',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required'     => 'Title cannot be empty if provided.',
            'course_id.exists'   => 'The selected course is invalid.',
            'order.integer'      => 'The order must be a number.',

            'files.array'   => 'The files must be sent as an array.',
            'files.*.file'  => 'One of the uploaded attachments is not a valid file.',
            'files.*.mimes' => 'Only PDF, DOC, ZIP, JPG, PNG, and MP4 files are allowed.',
            'files.*.max'   => 'Individual files cannot exceed 50MB.',
        ];
    }
}
