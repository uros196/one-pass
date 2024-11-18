<?php

namespace App\Services\Encryption\Basic;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

/**
 * Class for creating and retrieving an encryption key that will be used in a Basic Encryption system.
 * Its combine a raw user's 'Master Password' and encryption public key which is
 * unique by the user (key made in the registration process).
 * Such a key will be stored in the session for easier use.
 * Basis Encryption system is used for encryption less sensitive data such as email, usernames, etc.
 */
class BasicSignature
{
    protected const SESSION_KEY = '_basicEncryptionKey';

    /**
     * Get the encryption key that uses for basic encryption/decryption.
     *
     * @return string|JsonResponse|RedirectResponse
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function get(): string|JsonResponse|RedirectResponse
    {
        if (!session()->has(self::SESSION_KEY)) {
            return request()->expectsJson()
                ? response()->json(['message' => __('encryption.password_confirmation'), 423])
                : redirect()->guest(route('password.confirm'));
        }

        return session()->get(self::SESSION_KEY);
    }

    /**
     * Set the encryption key that uses for basic encryption/decryption.
     *
     * @param LoginRequest $request
     * @return void
     */
    public static function set(LoginRequest $request): void
    {
        session()->put(
            self::SESSION_KEY,
            self::make($request->user(), $request->validated('password'))
        );
    }

    /**
     * Make the basic encryption key.
     *
     * @param User $user
     * @param string $master_password
     *
     * @return string
     */
    protected static function make(User $user, #[\SensitiveParameter] string $master_password): string
    {
        // make a simple signature by combining raw user Master Password and user ID
        $signature = hash('sha256', sprintf('%s.$1gN.%s', $master_password, $user->id));
        // cut the final string so the Master Password cannot be guessed
        return Str::substr($signature, 0, 32);
    }
}
