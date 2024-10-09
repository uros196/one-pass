<?php

namespace App\Services\Encryption;

use App\Models\User;

/**
 * Master key is that only a user knows.
 * We are using this key to unlock Encryption Key that is used for encryption-sensitive data.
 */
class MasterKey
{
    /**
     * Holds current master key.
     *
     * @var string|null $master_key
     */
    protected static ?string $master_key = null;

    /**
     * Make the hashed master key.
     *
     * @param string $master_key
     * @param User $user
     *
     * @return string
     */
    public static function make(string $master_key, User $user): string
    {
        $key = hash('sha256', "{$master_key}.". hash('crc32b', $user->id));
        self::set($master_key);

        return $key;
    }

    /**
     * Set the master key.
     *
     * @param string $master_key
     * @return void
     */
    public static function set(string $master_key): void
    {
        if (!self::exists()) {
            self::$master_key = $master_key;
        }
    }

    /**
     * Get the master key.
     *
     * @return string|null
     */
    public static function get(): string|null
    {
        return self::$master_key;
    }

    /**
     * Check if a master key is set or not.
     *
     * @return bool
     */
    public static function exists(): bool
    {
        return !is_null(self::$master_key);
    }
}
