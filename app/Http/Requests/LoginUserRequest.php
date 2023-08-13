<?php

namespace App\Http\Requests;

use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
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
            'password' => ['required', 'min:8'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->login_email) || isset($this->email_login)) {
            $index = ($this->login_email) ?? ($this->email_login);

            $userLogin = User::where('login', $index)
                ->orWhere('email', $index)->get()
                ->pluck('login')->toArray();

        } elseif (isset($this->login)) {
            $userLogin = User::where('login', $this->login)
                ->get()->pluck('login')->toArray();
        } elseif (isset($this->email)) {
            $userLogin = User::where('email', $this->email)
                ->get()->pluck('login')->toArray();
        } else {
            $userLogin = [];
        }

        $userLogin = ['login' => $userLogin[0] ?? ''];

        $this->merge($userLogin);
    }
}
