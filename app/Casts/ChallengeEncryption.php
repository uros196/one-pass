<?php

namespace App\Casts;

use App\Services\Encryption\Challenge\ChallengeEncrypter;
use App\Services\Encryption\Challenge\ChallengeMasterKey;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TGet
 * @template TSet
 */
class ChallengeEncryption implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array<string, mixed> $attributes
     *
     * @return TGet|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->hasMasterKey()
            ? $this->encrypter()->decrypt($value)
            : '••••••••••••••••••';
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param TSet|null $value
     * @param array<string, mixed> $attributes
     *
     * @return string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (!$this->hasMasterKey()) {
            throw new \InvalidArgumentException(__('encryption.master_key.missing'));
        }

        return $this->encrypter()->encrypt($value);
    }

    /**
     * Get the encrypter object.
     *
     * @return ChallengeEncrypter
     */
    protected function encrypter(): ChallengeEncrypter
    {
        return app(ChallengeEncrypter::class);
    }

    /**
     * Check if the 'Master Key' exists or not.
     *
     * @return bool
     */
    protected function hasMasterKey(): bool
    {
        return ChallengeMasterKey::exists();
    }
}
