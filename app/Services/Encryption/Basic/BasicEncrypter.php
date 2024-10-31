<?php

namespace App\Services\Encryption\Basic;

use App\Services\Encryption\Encrypter;

class BasicEncrypter extends Encrypter
{
    /**
     * Get the encryption key used by Basic Encryption system.
     *
     * @return string
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function usingKey(): string
    {
        return BasicEncryptionKey::get();
    }
}
