<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route("user") ? $this->route("user")->id : null;

        return [

            "name" => ["sometimes", "required", "string", "max:255"],
            "email" => [
                "sometimes",
                "required",
                "string",
                "email",
                "max:255",
                Rule::unique("users")->ignore($userId),
            ],

            "password" => ["sometimes", "nullable", "string", Password::defaults(), "confirmed"],
        ];
    }
}

