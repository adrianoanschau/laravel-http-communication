<?php

namespace App\Http\Requests;

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
            "firstname" => "required|string",
            "lastname" => "required|string",
            "email" => "required|string|email|unique:users",
            "username" => "required|string|unique:users|min:4",
            "password" => "required|min:8"
        ];
    }
}
