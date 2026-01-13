<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID field is mandatory.',
            'user_id.exists'   => 'The selected user does not exist in our records.',
            'role.required'    => 'Please specify a role to assign.',
            'role.exists'      => 'The specified role is invalid or not defined.',
        ];
    }
}
