<?php

namespace App\Providers;

use App\Listeners\AccountLockedListener;
use App\Listeners\CreateEncryptionKeyListener;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected array $listen = [
        // handle user registration
        Registered::class => [
            SendEmailVerificationNotification::class,
            CreateEncryptionKeyListener::class
        ],
        // handle account verification
        Verified::class => [

        ],
        // handle password reset
        PasswordReset::class => [
            // TODO: start the process of making a new key and re-encrypting user's data
        ],
        // handle exceeded maximum login attempts
        Lockout::class => [
            AccountLockedListener::class
        ]
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
