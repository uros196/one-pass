<?php

namespace App\Http\Requests\Auth;

use App\Traits\AuthorizeUnlockRequest;

class UnlockAccountRequest extends LoginRequest
{
    use AuthorizeUnlockRequest;

    /**
     * We use this request to validate user's password before unlock the account.
     * So basically we need the all functionalities form 'LoginRequest' because
     * we need to validate the user and login in on successful.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->decryptEmail()
        ]);
    }

    /**
     * Mark the account as unlocked so we can pass the authentication.
     *
     * @return bool
     */
    protected function isAccountLocked(): bool
    {
        return false;
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
