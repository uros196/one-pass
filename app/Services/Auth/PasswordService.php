<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class PasswordService
{
    /**
     * Update the user's password.
     *
     * @param UpdatePasswordRequest $request
     * @return User
     */
    public function update(UpdatePasswordRequest $request): User
    {
        $request->user()->update($request->only('password'));

        return $request->user();
    }

    /**
     * Set user's new password.
     *
     * @param NewPasswordRequest $request
     * @return mixed
     */
    public function new(NewPasswordRequest $request): mixed
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        return \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => $request->password,
                    'remember_token' => Str::random(60),
                ])->save();

                // fire password reset event
                event(new PasswordReset($user));
            }
        );
    }
}
