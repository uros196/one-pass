<?php

namespace App\Services\Encryption;

use App\Http\Requests\Encryption\MasterKeyRequest;
use App\Models\User;
use App\Services\Encryption\Token\TokenFactory;

/**
 * 'Master Password' is a thing that only a user knows.
 * We're using a 'Master Password' for making a 'Master Key'.
 * And a 'Master Key' is a key factor for unlocking 'Encryption Key' that is used for encrypt/decrypt sensitive data.
 */
class MasterKey
{
    /**
     * MasterKey constructor.
     *
     * Use 'MasterKeyRequest' request for accessing data we need
     *
     * @param MasterKeyRequest $request
     */
    public function __construct(protected MasterKeyRequest $request)
    {
        // start validation in case someone tries to pass an empty request (created manually)
        // or non-validated request
        if (empty($this->request->validated())) {
            $this->request->validateResolved();
        }
    }

    /**
     * Holds current master key.
     *
     * @var string|null $master_key
     */
    protected static ?string $master_key = null;

    /**
     * Make the hashed Master Key.
     *
     * @param string $master_password
     * @param User $user
     *
     * @return string
     */
    public static function make(string $master_password, User $user): string
    {
        $key = hash('sha256', "{$master_password}.". hash('crc32b', $user->id));
        self::set($master_password);

        return $key;
    }

    /**
     * Set the Master Key.
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
     * Get the Master Key.
     *
     * @return string|null
     */
    public static function get(): string|null
    {
        return self::$master_key;
    }

    /**
     * Check if a 'Master Key' is set or not.
     *
     * @return bool
     */
    public static function exists(): bool
    {
        return !is_null(self::$master_key);
    }

    /**
     * Encrypt 'Master Key'.
     *
     * @return string
     */
    public function encrypt(): string
    {
        return $this->encrypter()->encrypt(
            self::make($this->request->validated('password'), $this->request->user())
        );
    }

    /**
     * Decrypt 'Master Key' using a provided encryption token.
     *
     * @return string
     */
    public function decrypt(): string
    {
        $master_key = $this->encrypter()->decrypt(
            $this->request->user()->encryptionToken->master_key
        );

        self::set($master_key);
        return $master_key;
    }

    /**
     * Get the 'Encryption Key', a key for encrypting/decrypting a 'Master Key'.
     *
     * @return string|null
     */
    protected function getEncryptionKey(): string|null
    {
        // we 'salt' the token in case it becomes exposed,
        // so it cannot be used outside the environment where is it created
        return TokenFactory::salt(
            // TODO: consider how to resolve this problem, we're modifying request object while creating an encryption token
            // $this->request->validated('encryption_token')
            $this->request->encryption_token
        );
    }

    /**
     * Get the encryptor that is configured specifically for 'Master Key'.
     *
     * @return Encrypter
     */
    protected function encrypter(): Encrypter
    {
        return app(Encrypter::class)->usingKey($this->getEncryptionKey());
    }
}
