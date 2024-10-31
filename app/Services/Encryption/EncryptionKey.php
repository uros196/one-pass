<?php

namespace App\Services\Encryption;

use Illuminate\Support\Str;

class EncryptionKey
{
    /**
     * Default encryption algorithm.
     *
     * @var string $algorithm
     */
    protected string $algorithm = 'sha256';

    /**
     * Default number of iterations.
     *
     * @var int $iterations
     */
    protected int $iterations = 10000;

    /**
     * Default generated key length.
     *
     * @var int $length
     */
    protected int $length = 32;

    /**
     * Request binary output by default.
     *
     * @var bool $binary
     */
    protected bool $binary = true;

    /**
     * Sign the final encryption key with the unique signature.
     *
     * @var string|null $signature
     */
    protected ?string $signature = null;

    /**
     * Final generated encryption key.
     *
     * @var string|null $encryption_key
     */
    protected ?string $encryption_key = null;

    /**
     * EncryptionKey constructor.
     *
     * @param string $public_key
     */
    public function __construct(protected string $public_key) {}

    /**
     * Make the public key.
     *
     * @return self
     */
    public static function makePublicKey(): self
    {
        return new static(Str::random(128));
    }

    /**
     * Make the object for the given public key.
     *
     * @param string $public_key
     * @return $this
     */
    public static function makeFrom(string $public_key): self
    {
        return new static($public_key);
    }

    /**
     * Sign the public key with the signature (mostly unique key by the user).
     *
     * @param string|null $signature
     * @return $this
     */
    public function sign(?string $signature): self
    {
        return $this->setProperty($signature, 'signature');
    }

    /**
     * Define what algorithm you want to use for generating an encryption key.
     *
     * @param string $algorithm
     * @return $this
     */
    public function useAlgorithm(string $algorithm): self
    {
        return $this->setProperty($algorithm, 'algorithm');
    }

    /**
     * Set number of the iteration
     *
     * @param int $iterations
     * @return $this
     */
    public function setIterations(int $iterations): self
    {
        return $this->setProperty($iterations, 'iterations');
    }

    /**
     * Set the key length.
     *
     * @param int $length
     * @return $this
     */
    public function setLength(int $length): self
    {
        return $this->setProperty($length, 'length');
    }

    /**
     * Use binary output.
     *
     * @param bool $binary
     * @return $this
     */
    public function useBinary(bool $binary = true): self
    {
        return $this->setProperty($binary, 'binary');
    }

    /**
     * Get the public key only.
     *
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->public_key;
    }

    /**
     * Generate a final encryption key.
     *
     * @return string
     */
    public function generate(): string
    {
        if (is_null($this->encryption_key)) {
            $this->encryption_key = hash_pbkdf2(
                $this->algorithm,               // hash algorithm
                $this->public_key,              // public key that will be transformed
                $this->signature ?? '',     // salt (user signature)
                $this->iterations,              // number if iterations, higher is better
                $this->length,                  // key length
                $this->binary                   // request binary output
            );
        }

        return $this->encryption_key;
    }

    /**
     * Set the $value for the given property only if its value is different.
     * After setting a new value, request new key generation.
     *
     * @param mixed $value
     * @param string $property
     *
     * @return $this
     */
    protected function setProperty(mixed $value, string $property): self
    {
        if ($this->{$property} !== $value) {
            $this->{$property} = $value;
            $this->encryption_key = null;
        }

        return $this;
    }
}
