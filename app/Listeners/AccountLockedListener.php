<?php

namespace App\Listeners;

use App\Models\User;
use App\Support\SystemAlert;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountLockedListener
{
    /**
     * Inform the user that his account is locked.
     *
     * @param Lockout $event
     * @return void
     */
    public function handle(Lockout $event): void
    {
        $this->sendNotification($event->request);

        // this message will be displayed on the login page
        SystemAlert::warning(__('auth.locked'))->toSession();

        // because we use this request in many scenarios, log out the user just in case
        Auth::logout();
    }

    /**
     * Inform the user that his account has been locked.
     *
     * @param Request $request
     * @return void
     */
    protected function sendNotification(Request $request): void
    {
        $user = $request->user() ?? User::where('email', $request->email)->first();

        $user->sendUnlockNotification();
    }
}
