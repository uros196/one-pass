<?php

namespace App\Models\Concerns;

use App\Http\Requests\Encryption\MasterKeyRequest;
use App\Models\EncryptionToken;
use App\Services\Encryption\MasterKey;
use App\Services\Encryption\Token\TokenFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

trait HasEncryptionToken
{
    /**
     * Get the related encryption token.
     *
     * @return HasOne
     */
    public function encryptionToken(): HasOne
    {
        return $this->hasOne(EncryptionToken::class);
    }

    /**
     * Create the encryption token.
     * This token will be used for decrypting 'Master Key'.
     *
     * This short-life token allows us the ability to 'unlock' encrypted data without
     * providing 'Master Password' every time.
     *
     * @param MasterKeyRequest $request
     * @return string
     */
    public function createToken(MasterKeyRequest $request): string
    {
        $token = $this->generateTokenString();

        // modify request so 'Master Key' can be encrypted successfully
        $request->merge([
            'encryption_token' => $token
        ]);

        $this->encryptionToken->create([
            'token' => TokenFactory::create($token),
            'master_key' => app(MasterKey::class, ['request' => $request])->encrypt(),
            'session_id' => session()->id(),

            // TODO: read this from the user's config
            'expires_at' => now()->addMinutes(config('auth.encryption_token.expire'))
        ]);

        return $token;
    }

    /**
     * Generate token string.
     *
     * @return string
     */
    public function generateTokenString(): string
    {
        $plain_token = sprintf(
            '%s|%s|%s',
            session()->id(),
            Str::random(40),
            hash('crc32b', $this->email)
        );

        return Str::substr(hash('sha512', $plain_token), rand(0, 96), 32);
    }
}
