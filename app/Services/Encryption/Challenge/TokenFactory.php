<?php

namespace App\Services\Encryption\Challenge;

use App\Models\EncryptionToken;

class TokenFactory
{
    /**
     * Verify token validity.
     *
     * @param EncryptionToken $model
     * @param string $token
     * @return bool
     */
    public static function verify(EncryptionToken $model, string $token): bool
    {
        return hash_equals($model->token, self::create($token));
    }

    /**
     * Check if the token is expired or not.
     *
     * @param EncryptionToken $model
     * @return bool
     */
    public static function isExpired(EncryptionToken $model): bool
    {
        return $model->expires_at->isPast();
    }

    /**
     * Extend token life.
     *
     * @param EncryptionToken $model
     * @return void
     */
    public static function extendTokenLife(EncryptionToken $model): void
    {
        $model->forceFill([
            'last_used_at' => now(),
            'expires_at'   => now()->addMinutes(config('auth.encryption_token.expire')),
        ])->save();
    }

    /**
     * Create hashed token that we're storing into DB.
     *
     * @param string $token
     * @return string
     */
    public static function create(string $token): string
    {
        return hash('sha256', self::salt($token));
    }

    /**
     * Salt the token with the data from the request,
     * so it cannot be used outside the environment where is it created.
     *
     * @param string $token
     * @return string
     */
    public static function salt(string $token): string
    {
        $salted_token = "$token|". hash('crc32b', request()->ip());

        return hash('sha512', $salted_token);
    }
}
