<?php

namespace App\Services\SensitiveData\Resolvers;

use App\Contracts\SensitiveData\Resolvers\DataModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModelResolver implements DataModel
{
    use InteractWithRequest;

    /**
     * Holds instance of a resolved model.
     *
     * @var Model $model
     */
    protected Model $model;

    /**
     * Get the resolved model.
     *
     * @return Model
     */
    public function get(): Model
    {
        return $this->model;
    }

    /**
     * Resolve the model, empty or with the data.
     * It depends on the required action.
     *
     * @return self
     *
     * @throws ModelNotFoundException
     */
    public function resolve(): self
    {
        $type  = $this->getType();
        $model = config("sensitive_data.connections.{$type}.model");

        $this->model = ($id = $this->getId())
            ? $model::findOrFail($id)
            : new $model;

        return $this;
    }
}
