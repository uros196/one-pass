<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegistrationService
{
    /**
     * Register/create a user.
     *
     * @param RegistrationRequest $request
     * @return User
     */
    public function __invoke(RegistrationRequest $request): User
    {
        $user = User::create($request->validated());

        // fire registered event
        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

}
