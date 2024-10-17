<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\Auth\LoginRequest;

class ConfirmPasswordRequest extends LoginRequest
{
    /**
     * Get the email from the authenticated user and prepare it for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->user()->email,
        ]);
    }

    /**
     * Define a name to attach a validation error message to it.
     *
     * @return string
     */
    protected function validationFieldName(): string
    {
        return 'password';
    }
}
