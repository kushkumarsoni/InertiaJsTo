<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'email|required|unique:users,email',
            'mobile' => 'required|numeric|digits:10',
            'password' =>'required',
            'image' => 'nullable',
            'gender' => 'nullable',
            'is_active' => 'nullable',
            'dob' => 'nullable',
            'hobbies' => 'nullable',
        ];
    }
}
