<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginUserRequest extends FormRequest
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
            'login' => ['regex:/^[\w\._\-\d]+$/i'],
            'email' => ['email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->login)) {
            $user = User::where('login', $this->login)->get()->pluck('login')->toArray();
        } elseif (isset($this->email)) {
            $user = User::where('email', $this->email)->get()->pluck('email')->toArray();
        } else {
            $user = [];
        }



        $this->merge($user);
    }
}
