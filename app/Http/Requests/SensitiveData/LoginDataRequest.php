<?php

namespace App\Http\Requests\SensitiveData;

use Illuminate\Foundation\Http\FormRequest;

class LoginDataRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'note' => 'nullable|string|max:500',
        ];
    }

}
