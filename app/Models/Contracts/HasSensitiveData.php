<?php

namespace App\Models\Contracts;

interface HasSensitiveData
{
    /**
     * Check if sensitive attributes is defined.
     *
     * @return bool
     */
    public function hasSensitiveAttributes(): bool;

    /**
     * Check if attribute is defined as sensitive or not.
     *
     * @param string $key
     * @return bool
     */
    public function isAttributeSensitive(string $key): bool;

    /**
     * Get defined sensitive attributes.
     *
     * @return array
     */
    public function getSensitiveAttributes(): array;

    /**
     * Encrypt sensitive data.
     *
     * @param mixed $data
     * @return string
     */
    public function encrypt(#[\SensitiveParameter] mixed $data): string;

    /**
     * Decrypt sensitive data.
     *
     * @param string $data
     * @return mixed
     */
    public function decrypt(string $data): mixed;
}
