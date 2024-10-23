<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RequirePassword as Middleware;
use Illuminate\Support\Facades\Session;

class RequirePassword extends Middleware
{
    /**
     * Track user activity and update the time of password confirmation even if user is not confirmed it.
     * On this way, we're tracking user's last activity and require password confirmation after timeout exceeded.
     * Otherwise, password confirmation will be required after timeout no matter if the user had some action or not.
     *
     * @param $request
     * @param $passwordTimeoutSeconds
     *
     * @return bool
     */
    protected function shouldConfirmPassword($request, $passwordTimeoutSeconds = null): bool
    {
        $should_confirm = parent::shouldConfirmPassword($request, $passwordTimeoutSeconds);

        if (!$should_confirm) {
            Session::passwordConfirmed();
        }

        return $should_confirm;
    }
}
