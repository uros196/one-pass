<?php

namespace App\Services\SensitiveData;

use App\Contracts\SensitiveData\DataFormRequest;
use App\Contracts\SensitiveData\DataModel;
use App\Contracts\SensitiveData\DataRegistrar;
use App\Contracts\SensitiveData\DataResource;
use App\Services\SensitiveData\Resolvers\FormRequestResolver;
use App\Services\SensitiveData\Resolvers\ModelResolver;
use App\Services\SensitiveData\Resolvers\RegistrarResolver;
use App\Services\SensitiveData\Resolvers\ResourceResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Router
{
    /**
     * Router constructor.
     *
     * @param Request $request
     */
    public function __construct(protected Request $request) {}

    /**
     * Get the available sensitive data types
     *
     * @return array
     */
    public static function getAvailableTypes(): array
    {
        return array_keys(config('sensitive_data.connections'));
    }

    /**
     * Get the type name assigned to the model.
     *
     * @param string $model_name
     * @return string|null
     */
    public static function getTypeByModel(string $model_name): ?string
    {
        return Arr::get(array_flip(array_map(function ($item) {
            return $item['model'];
        }, config('sensitive_data.connections'))), $model_name);
    }

    /**
     * Resolve data FormRequest.
     *
     * @return DataFormRequest
     */
    public static function resolveFormRequest(): DataFormRequest
    {
        return (new FormRequestResolver)->resolve();
    }

    /**
     * Resolve data Resource.
     *
     * @return DataResource
     */
    public static function resolveResource(): DataResource
    {
        return (new ResourceResolver)->resolve();
    }

    /**
     * Resolve data Registrar.
     *
     * @return DataRegistrar
     */
    public static function resolveRegistrar(): DataRegistrar
    {
        return (new RegistrarResolver)->resolve();
    }

    /**
     * Resolve data Model.
     *
     * @return DataModel
     */
    public static function resolveModel(): DataModel
    {
        return (new ModelResolver)->resolve();
    }
}
