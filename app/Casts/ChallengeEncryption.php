<?php

namespace App\Casts;

use App\Services\Encryption\Challenge\ChallengeSignature;
use App\Services\Encryption\Encrypter;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TGet
 * @template TSet
 */
class ChallengeEncryption implements Castable
{
    /**
     * Get the name of the caster class to use when casting from / to this cast target.
     *
     * @param array $arguments
     * @return CastsAttributes
     */
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class($arguments) implements CastsAttributes
        {
            use HasAttributes;

            public function __construct(protected array $arguments) {}

            /**
             * Cast the given value.
             */
            public function get(Model $model, string $key, mixed $value, array $attributes): mixed
            {
                if (is_null($value)) {
                    return null;
                }

                return $this->encrypter()->canDecrypt($value)
                    ? $this->cast($key, $this->encrypter()->decrypt($value))
                    : '••••••••••••••••••';
            }

            /**
             * Prepare the given value for storage.
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

            /**
             * Implement value casting if requested.
             *
             * @param string $key
             * @param mixed $value
             *
             * @return mixed
             */
            protected function cast(string $key, mixed $value): mixed
            {
                // set requested casts so 'HasAttributes' can work with
                $this->casts = $this->ensureCastsAreStringValues(array_merge(
                    $this->casts, [$key => head($this->arguments)]
                ));

                return !empty($this->arguments)
                    ? $this->castAttribute($key, $value)
                    : $value;
            }

            /**
             * This method is taken from the model so 'HasAttributes' can work with.
             *
             * @return bool
             */
            public function getIncrementing(): bool
            {
                return false;
            }
        };
    }

    /**
     * If you want to apply casting method to your encrypted value,
     * use this method and pass cast method you want.
     *
     * It works exactly the same as on model -> date:Y-d-m
     *  You can pass Laravel casts or custom cast class.
     *
     * This cast will be applied after the value is decrypted.
     *
     * @param string $cast
     * @return string
     */
    public static function after(string $cast): string
    {
        return static::class .':'. $cast;
    }
}
