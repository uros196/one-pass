<?php

namespace App\Services\SensitiveData\Resolvers;

use App\Contracts\Models\HasSensitiveData;
use App\Contracts\SensitiveData\DataModel;
use App\Contracts\SensitiveData\DataRegistrar;
use App\Contracts\SensitiveData\DataResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResolver implements DataResource
{
    use InteractWithRequest;

    /**
     * Holds resolved JsonResource object.
     *
     * @var JsonResource $resource
     */
    protected JsonResource $resource;

    /**
     * Get the resolved JsonResource object based on the data type.
     *
     * @return JsonResource
     */
    public function get(): JsonResource
    {
        return $this->resource;
    }

    /**
     * Resolve data resource.
     *
     * @return $this
     */
    public function resolve(): self
    {
        $resource_class = is_array($data = $this->model()->dataResource())
            ? $data[$this->currentRouteAction()]
            : $data;

        $this->resource = match ($this->currentRouteAction()) {
            'index' => $resource_class::collection(app(DataRegistrar::class)->list()),
            'show', 'edit' => $resource_class::make(app(DataModel::class)->get())
        };

        return $this;
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
