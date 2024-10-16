<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        $user = $event->request->user() ?? User::where('email', $event->request->email)->first();

        $user->sendUnlockNotification();
    }
}
