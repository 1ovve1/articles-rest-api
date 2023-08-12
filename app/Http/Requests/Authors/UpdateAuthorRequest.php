<?php

namespace App\Http\Requests\Authors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateAuthorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['string', 'email', 'unique:users,email', 'max:255'],
            'login' => ['unique:users,login', 'regex:/^[\w\._\-\d]+$/i'],
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'patronymic' => ['string', 'max:255'],
            'password' => ['string',  'confirmed', Password::min(8)],
            'active' => ['boolean'],
        ];
    }
}
