<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    use Traits\AuthorizeIfIsAuthenticated;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => "string|email|unique:users",
            "firstname" => "string",
            "lastname" => "string",
            "password" => "min:8",
            'reset_password' => "boolean"
        ];
    }
}
