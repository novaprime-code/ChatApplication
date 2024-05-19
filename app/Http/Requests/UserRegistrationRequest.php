<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

/**
 * @OA\Schema(
 *     schema="UserRegistrationRequest",
 *     type="object",
 *     required={"name", "email", "password", "phone_number", "address", "gender", "dob"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123"),
 *     @OA\Property(property="phone_number", type="string", example="+1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St, Springfield, IL"),
 *     @OA\Property(property="gender", type="string", enum={"male", "female", "Male", "Female"}, example="male"),
 *     @OA\Property(property="dob", type="string", format="date", example="1990-01-01")
 * )
 */

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:255|unique:users',
            'address' => 'required|string|max:255',
            'gender' => 'required|string|max:255|in:male,female,Male,Female',
            'dob' => 'required|date|max:255',
        ];

    }
}
