<?php

namespace App\Models\Concerns;

use App\Http\Requests\Encryption\ChallengeMasterKeyRequest;
use App\Models\EncryptionToken;
use App\Services\Encryption\Challenge\ChallengeMasterKey;
use App\Services\Encryption\Challenge\TokenFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

trait HasEncryptionToken
{
    /**
     * Get the related encryption token (based on the current session).
     *
     * @return HasOne
     */
    public function encryptionToken(): HasOne
    {
        return $this->hasOne(EncryptionToken::class)
            ->where('session_id', $this->sessionForeignKey());
    }

    /**
     * Create the encryption token.
     * This token will be used for decrypting 'Master Key'.
     *
     * This short-life token allows us the ability to 'unlock' encrypted data without
     * providing 'Master Password' every time.
     *
     * @param ChallengeMasterKeyRequest $request
     * @return string
     */
    public function createToken(ChallengeMasterKeyRequest $request): string
    {
        $token = $this->generateTokenString();

        $model = $this->encryptionToken()->create([
            'token' => TokenFactory::create($token),
            'master_key' => 'encryption key preparing...',
            'session_id' => $this->sessionForeignKey(),

            // TODO: read this from the user's config
            'expires_at' => now()->addMinutes(config('auth.encryption_token.expire'))
        ]);

        // modify the main request and inject created token and request FULL validation
        request()->merge([
            'encryption_token'  => $token,
            'validate_all'      => true
        ]);

        // use modified request (with the token) to encrypt a challenge Master Key
        $model->forceFill([
            'master_key' => app(ChallengeMasterKey::class)->encrypt(),
        ])->save();

        return $token;
    }

    /**
     * Generate token string.
     *
     * @return string
     */
    public function generateTokenString(): string
    {
        return sprintf(
            '%s-%s-%s',
            session()->id(), Str::random(40), hash('crc32b', $this->email)
        );
    }

    /**
     * Generate foreign key value.
     *
     * @return string
     */
    protected function sessionForeignKey(): string
    {
        $name = '_sessionForeignKey';

        if (!session()->has($name)) {
            session()->put($name, Str::random(40));
        }

        return session()->get($name);
    }

    /**
     * Regenerate foreign key for existing encryption token.
     *
     * @return void
     */
    public function regenerateTokenForeignKey(): void
    {
        // load the relation if not loaded
        if (!$this->relationLoaded('encryptionToken')) {
            $this->load('encryptionToken');
        }

        // clear previous value
        session()->forget('_sessionForeignKey');

        $this->encryptionToken?->forceFill([
            'session_id' => $this->sessionForeignKey(),
        ])->save();
    }
}
