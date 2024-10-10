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
     * Encrypter constructor.
     */
    public function __construct()
    {
        $this->user = request()->user();
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
     * Get encrypter with a custom encryption key that is unique by user.
     *
     * @return BaseEncrypter
     */
    public function getEncrypter(): BaseEncrypter
    {
        return new BaseEncrypter(
            key: EncryptionKey::get($this->user),
            cipher: config('app.cipher')
        );
    }
}
