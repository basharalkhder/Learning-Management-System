<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone'      => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender'     => 'nullable|in:male,female',
        ];
    }

    public function messages(): array
    {
        return [
           
            'name.required'      => 'The full name is required for account creation.',
            'email.required'     => 'An email address is mandatory for registration.',
            'email.email'        => 'Please provide a valid email format.',
            'email.unique'       => 'This email is already registered in our system.',

            
            'password.required'  => 'A password is required to secure your account.',
            'password.min'       => 'The password must be at least 8 characters long.',

            
            'phone.max'          => 'The phone number must not exceed 20 characters.',
            'birth_date.date'    => 'Please provide a valid date for the birth date field.',
            'gender.in'          => 'The gender must be either male or female.',
        ];
    }
}
