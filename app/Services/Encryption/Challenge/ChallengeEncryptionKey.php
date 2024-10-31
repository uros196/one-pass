<?php

namespace App\Services\Encryption\Challenge;

use App\Models\User;
use Illuminate\Support\Str;

class ChallengeEncryptionKey
{
    /**
     * Create a unique part of the encryption key based on user data.
     *
     * @param User $user
     * @return string
     */
    public static function makeFor(User $user): string
    {
        /*
         * this is complex solution but more unique string will be generated with Str::random()
         *
        $string = sprintf(
            '%s|%s|%d|%s',
            $user->id, $user->email, now()->timestamp, Str::random(64)
        );
        $hashed = hash('sha512', $string);

        // encryption key length
        $key_length = 128;

        // dynamically choose start point
        $start_at = rand(0, max((Str::length($hashed) - $key_length), 1));

        return Str::substr($hashed, $start_at, $key_length);
         *
         */

        return Str::random(128);
    }

    /**
     * Get the final encryption key (unique by user) for encryption/decryption sensitive data.
     *
     * @param User $user
     * @return string
     */
    public static function get(User $user): string
    {
        // generate the string that is a combination of the APP and user data
        $plain_text_key = sprintf(
            '%s|%s|%s',
            config('app.key'),
            ChallengeMasterKey::get(),
            $user->encryptionKey->key
        );

        return Str::substr(hash('sha256', $plain_text_key), 16, 32);
    }
}
