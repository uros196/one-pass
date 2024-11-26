<?php

namespace App\Casts;

use App\Services\Encryption\Challenge\ChallengeSignature;
use App\Services\Encryption\Encrypter;
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
        if (is_null($value)) {
            return null;
        }

        return $this->encrypter()->canDecrypt($value)
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
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return null;
        }

        // TODO: maybe return 'encryption_token' validation error
        if (!ChallengeSignature::exists()) {
            throw new \InvalidArgumentException('Encryption Error! Challenge Signature is missing.');
        }

        return $this->encrypter()->encrypt($value);
    }

    /**
     * Get the encrypter object.
     *
     * @return Encrypter
     */
    protected function encrypter(): Encrypter
    {
        return app('challenge-encrypter');
    }
}
