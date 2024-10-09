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
