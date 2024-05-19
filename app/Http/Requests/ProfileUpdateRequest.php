<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'password' => 'required|string|min:8',
            'phone_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:255|in:male,female,Male,Female',
            'dob' => 'required|date|max:255',
        ];

    }
}
