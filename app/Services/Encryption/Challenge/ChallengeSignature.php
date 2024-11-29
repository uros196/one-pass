<?php

namespace App\Services\Encryption\Challenge;

use App\Http\Requests\Encryption\ChallengeSignatureRequest;
use App\Models\User;
use App\Services\Encryption\EncryptionKey;
use Illuminate\Encryption\Encrypter;

/**
 * 'Master Password' is a thing that only a user knows.
 * We're using a 'Master Password' for making a 'Challenge Signature'.
 * And a 'Challenge Signature' is a key factor for making 'Encryption Key' that is used for encrypt/decrypt sensitive data.
 */
class ChallengeSignature
{
    /**
     * MasterKey constructor.
     *
     * Use 'ChallengeSignatureRequest' request for accessing data we need.
     *
     * @param ChallengeSignatureRequest $request
     */
    public function __construct(protected ChallengeSignatureRequest $request)
    {
        // start validation in case someone tries to pass an empty request (created manually)
        // or non-validated request
        if (empty($this->request->validated())) {
            $this->request->validateResolved();
        }
    }

    /**
     * Holds current Challenge Signature.
     *
     * @var string|null $challenge_signature
     */
    protected static ?string $challenge_signature = null;

    /**
     * Make the hashed Challenge Signature.
     *
     * @param string $master_password
     * @param User $user
     *
     * @return string
     */
    public static function make(string $master_password, User $user): string
    {
        return hash_hkdf(
            algo: 'sha512',
            key: $master_password,
            length: 64,
            salt: hash('crc32b', $user->id)
        );
    }

    /**
     * Set the Challenge Signature.
     *
     * @param string $challenge_signature
     * @return void
     */
    public static function set(string $challenge_signature): void
    {
        if (!self::exists()) {
            self::$challenge_signature = $challenge_signature;
        }
    }

    /**
     * Get the Challenge Signature.
     *
     * @return string|null
     */
    public static function get(): string|null
    {
        return self::$challenge_signature;
    }

    /**
     * Check if a 'Challenge Signature' is set or not.
     *
     * @return bool
     */
    public static function exists(): bool
    {
        return !is_null(self::$challenge_signature);
    }

    /**
     * Encrypt 'Challenge Signature'.
     *
     * @return string
     */
    public function encrypt(): string
    {
        return $this->encrypter()->encrypt(
            self::make($this->request->validated('master_password'), $this->request->user())
        );
    }

    /**
     * Decrypt 'Challenge Signature' using a provided encryption token.
     *
     * @return string
     */
    public function decrypt(): string
    {
        $master_key = $this->encrypter()->decrypt(
            $this->request->user()->encryptionToken->signature
        );

        self::set($master_key);
        return $master_key;
    }

    /**
     * Get the 'Encryption Key', a key for encrypting/decrypting a 'Challenge Signature' from DB.
     *
     * @return EncryptionKey
     */
    protected function getEncryptionKey(): EncryptionKey
    {
        // we 'salt' the token in case it becomes exposed,
        // so it cannot be used outside the environment where is it created
        $public_key = TokenFactory::salt(
             $this->request->validated('encryption_token')
        );

        return EncryptionKey::makeFrom($public_key);
    }

    /**
     * Get the encrypter that is configured specifically for 'Challenge Signature'.
     *
     * @return Encrypter
     */
    protected function encrypter(): Encrypter
    {
        return new Encrypter(
            $this->getEncryptionKey()->setLength(32)->generate(),
            config('app.cipher')
        );
    }
}
