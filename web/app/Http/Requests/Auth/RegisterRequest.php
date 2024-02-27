<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "firstname" => ["required", "string"],
            "lastname" => ["required", "string"],
            "email" => ["required", "string", "email"],
            "username" => ["required", "string", "min:4"],
            "password" => ['required', 'confirmed'],
        ];
    }
}
