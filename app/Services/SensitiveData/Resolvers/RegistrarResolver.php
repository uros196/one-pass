<?php

namespace App\Services\SensitiveData\Resolvers;

use App\Contracts\Models\HasSensitiveData;
use App\Contracts\SensitiveData\DataRegistrar;

class RegistrarResolver
{
    use InteractWithRequest;

    /**
     * Get resolved DataRegistrar object that's based on the data type.
     *
     * @return DataRegistrar
     */
    public function resolve(): DataRegistrar
    {
        return app($this->model()->dataRegistrar());
    }

    /**
     * Get 'HasSensitiveData' object.
     *
     * @return HasSensitiveData
     */
    protected function model(): HasSensitiveData
    {
        $type = $this->getType();
        return new (config("sensitive_data.connections.{$type}.model"));
    }
}
