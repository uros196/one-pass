<?php

namespace App\Casts;

use App\Services\Encryption\Encrypter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TGet
 * @template TSet
 */
class BasicEncryption implements CastsAttributes
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
        return $this->encrypter()->decrypt($value);
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
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->encrypter()->encrypt($value);
    }

    /**
     * Get the encrypter object.
     *
     * @return Encrypter
     */
    protected function encrypter(): Encrypter
    {
        return app('basic-encrypter');
    }
}
