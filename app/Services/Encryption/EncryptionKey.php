<?php

namespace App\Services\Encryption;

use App\Models\User;
use Illuminate\Support\Str;

class EncryptionKey
{
    /**
     * Create a unique part of the encryption key based on user data.
     *
     * @param User $user
     * @return string
     */
    public static function makeFor(User $user): string
    {
        $string = sprintf(
            '%s|%s|%d|%s',
            $user->id, $user->email, now()->timestamp, Str::random(32)
        );

        return Str::substr(hash('sha512', $string), rand(0, 64), 64);
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
            MasterKey::get(),
            $user->encryptionKey->key
        );

        return Str::substr(hash('sha256', $plain_text_key), 16, 32);
    }
}
