<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\PredefinedRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => PredefinedRule::email(User::class),
            'password' => PredefinedRule::password(),
        ];
    }
}
