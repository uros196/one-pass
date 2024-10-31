<?php

namespace App\Services\Encryption\Challenge;

use App\Services\Encryption\Encrypter;
use Illuminate\Http\Request;

/**
 * This encrypter is using for challenging encryption.
 * That means the encryption key is more complicated. The user needs to confirm its Master Password
 * more often in order to create a Master Key. The system will create a short-life encryption token
 * that hides encryption key. That means a user can discover (in a short period of time) more than one
 * sensitive data by confirming its Master Password only once.
 *
 * To make it work, you must use 'VerifyEncryptionTokenMiddleware' middleware and encryption token
 * (in header or POST data).
 */
class ChallengeEncrypter extends Encrypter
{
    /**
     * ChallengeEncrypter constructor.
     *
     * @param Request $request
     */
    public function __construct(protected Request $request) {}

    /**
     * Get the encryption key used by Challenge Encryption system.
     *
     * @return string
     */
    protected function usingKey(): string
    {
        static $encryption_key = null;

        if (is_null($encryption_key)) {
            $encryption_key = ChallengeEncryptionKey::get($this->request->user());
        }

        return $encryption_key;
    }

}
