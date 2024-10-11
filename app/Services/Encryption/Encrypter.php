<?php

namespace App\Services\Encryption;

use App\Models\User;
use Illuminate\Encryption\Encrypter as BaseEncrypter;

class Encrypter
{
    /**
     * Holds current logged user model.
     *
     * @var User|mixed $user
     */
    protected User $user;

    /**
     * Key for encryption/decryption.
     *
     * @var string $key
     */
    protected string $key;

    /**
     * Encrypter constructor.
     */
    public function __construct()
    {
        $this->user = request()->user();

        // this encrypter is mainly used for unlock user's sensitive data
        // by default, we're setting user Encryption Key
        $this->usingKey(EncryptionKey::get($this->user));
    }

    /**
     * Encrypt sensitive data using a key specific by user.
     *
     * @param mixed $data
     * @return string
     */
    public function encrypt(#[\SensitiveParameter] mixed $data): string
    {
        return $this->getEncrypter()->encrypt($data);
    }

    /**
     * Decrypt data using a key specific by user.
     *
     * @param string $data
     * @return mixed
     */
    public function decrypt(string $data): mixed
    {
        return $this->getEncrypter()->decrypt($data);
    }

    /**
     * Using custom key.
     *
     * @param string $key
     * @return self
     */
    public function usingKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    // TODO: make 'usingKeys' function for setting previous keys variation (useful while changing Mater Password)

    /**
     * Get encrypter with a custom encryption key that is unique by user.
     *
     * @return BaseEncrypter
     */
    public function getEncrypter(): BaseEncrypter
    {
        return new BaseEncrypter(
            key: $this->key,
            cipher: config('app.cipher')
        );
    }
}
