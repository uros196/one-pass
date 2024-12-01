<?php

namespace App\Services\SensitiveData\Resolvers;

use App\Contracts\Models\HasSensitiveData;
use App\Contracts\SensitiveData\Resolvers\DataFormRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;

class FormRequestResolver implements DataFormRequest
{
    use InteractWithRequest;

    /**
     * Holds resolved FormRequest object.
     *
     * @var FormRequest $resolvedRequest
     */
    protected FormRequest $resolvedRequest;

    /**
     * Get the resolved FormRequest object.
     *
     * @return FormRequest
     */
    public function get(): FormRequest
    {
        return $this->resolvedRequest;
    }

    /**
     * Resolve FormRequest that's based on current data type.
     *
     * @return $this
     * @throws BindingResolutionException
     */
    public function resolve(): self
    {
        $form_request_class = is_array($data = $this->model()->dataFormRequest())
            ? $data[$this->currentRouteAction()]
            : $data;

        $this->resolvedRequest = app()->make($form_request_class);
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
