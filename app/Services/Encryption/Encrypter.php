<?php

namespace App\Services\Encryption;

use Illuminate\Encryption\Encrypter as BaseEncrypter;

abstract class Encrypter
{
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
     * @return string
     */
    abstract protected function usingKey(): string;

    // TODO: make 'usingKeys' function for setting previous keys variation (useful while changing Mater Password)

    /**
     * Get encrypter with a custom encryption key that is unique by user.
     *
     * @return BaseEncrypter
     */
    public function getEncrypter(): BaseEncrypter
    {
        return new BaseEncrypter(
            key: $this->usingKey(),
            cipher: config('app.cipher')
        );
    }
}
