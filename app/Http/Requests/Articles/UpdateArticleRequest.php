<?php

namespace App\Http\Requests\Articles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
            'slug' => ['alpha_dash', 'unique:articles,slug', 'max:255'],
            'title' => ['string', 'max:255'],
            'content' => ['string', 'max:10000'],
            'image_path' => ['required', 'string', 'max:255'],
            'active' => ['boolean'],
        ];
    }
}
