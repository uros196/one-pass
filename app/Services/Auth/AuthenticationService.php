<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\Password\NewPasswordRequest;
use App\Http\Requests\Auth\Password\UpdatePasswordRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;

class AuthenticationService
{
    /**
     * AuthenticationService constructor.
     *
     * @param RegistrationService $registration
     * @param PasswordService $password
     */
    public function __construct(
        protected RegistrationService $registration,
        protected PasswordService $password
    )
    {

    }

    /**
     * Register/create a user.
     *
     * @param RegistrationRequest $request
     * @return User
     */
    public function register(RegistrationRequest $request): User
    {
        return ($this->registration)($request);
    }

    /**
     * Update the user's password.
     *
     * @param UpdatePasswordRequest $request
     * @return User
     */
    public function updatePassword(UpdatePasswordRequest $request): User
    {
        return $this->password->update($request);
    }

    /**
     * Set user's new password.
     *
     * @param NewPasswordRequest $request
     * @return mixed
     */
    public function newPassword(NewPasswordRequest $request): mixed
    {
        return $this->password->new($request);
    }
}
