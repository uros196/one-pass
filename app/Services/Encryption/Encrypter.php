<?php

namespace App\Services\Encryption;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter as BaseEncrypter;

class Encrypter extends BaseEncrypter
{
    /**
     * Check if the data can be decrypted.
     *
     * @param string $data
     * @return bool
     */
    public function canDecrypt(string $data): bool
    {
        try {
            $this->decrypt($data);
            return true;
        } catch (DecryptException $e) {
            return false;
        }
    }
}
