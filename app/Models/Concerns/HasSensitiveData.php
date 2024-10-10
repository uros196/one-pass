<?php

namespace App\Models\Concerns;

use App\Services\Encryption\Encrypter;
use App\Services\Encryption\MasterKey;

/**
 * @property array $sensitive_data
 */
trait HasSensitiveData
{
    /**
     * Decrypt sensitive data.
     *
     * @param string $key
     * @return mixed
     */
    public function getAttributeValue($key): mixed
    {
        if (!$this->isAttributeSensitive($key)) {
            return parent::getAttributeValue($key);
        }

        $value = $this->decrypt($this->getRawOriginal($key));

        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }
        if ($this->hasAttributeMutator($key)) {
            return $this->mutateAttributeMarkedAttribute($key, $value);
        }

        return $value;
    }

    /**
     * Encrypt value for defined sensitive attribute.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function setAttribute($key, $value): mixed
    {
        if (!$this->isAttributeSensitive($key)) {
            return parent::setAttribute($key, $value);
        }

        $value = $this->encrypt($value);

        // first, we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // this model, such as "json_encoding" a listing of data for storage.
        if ($this->hasSetMutator($key)) {
            return $this->setMutatedAttributeValue($key, $value);
        }
        if ($this->hasAttributeSetMutator($key)) {
            return $this->setAttributeMarkedMutatedAttributeValue($key, $value);
        }

        return $this;
    }

    /**
     * Check if sensitive attributes is defined.
     *
     * @return bool
     */
    public function hasSensitiveAttributes(): bool
    {
        return property_exists($this, 'sensitive_data') && !empty($this->sensitive_data);
    }

    /**
     * Check if attribute is defined as sensitive or not.
     *
     * @param string $key
     * @return bool
     */
    public function isAttributeSensitive(string $key): bool
    {
        return in_array($key, $this->getSensitiveAttributes());
    }

    /**
     * Get defined sensitive attributes.
     *
     * @return array
     */
    public function getSensitiveAttributes(): array
    {
        return $this->hasSensitiveAttributes() ? $this->sensitive_data : [];
    }

    /**
     * Encrypt sensitive data using customized encrypter.
     *
     * @param mixed $data
     * @return string
     */
    public function encrypt(#[\SensitiveParameter] mixed $data): string
    {
        return app(Encrypter::class)->encrypt($data);
    }

    /**
     * Decrypt data using customized encrypter.
     * If a master key does not exist, retrieve hidden string.
     *
     * @param string $data
     * @return mixed
     */
    public function decrypt(string $data): mixed
    {
        return MasterKey::exists()
            ? app(Encrypter::class)->decrypt($data)
            : '••••••••••••';
    }
}
