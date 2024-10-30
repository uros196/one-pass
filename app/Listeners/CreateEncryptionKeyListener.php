<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\Encryption\Challenge\ChallengeEncryptionKey;
use Illuminate\Auth\Events\Registered;

class CreateEncryptionKeyListener
{
    /**
     * Create a key that will be used as a one part for encrypting sensitive data.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $user->encryptionKey()->create([
            'key' => ChallengeEncryptionKey::makeFor($user)
        ]);
    }
}
