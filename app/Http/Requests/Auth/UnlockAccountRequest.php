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
     * Proceed to the credential verification even if account is locked.
     *
     * @return bool
     */
    protected function proceedEvenIfLocked(): bool
    {
        return true;
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
