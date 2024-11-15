<?php

namespace App\Services\SensitiveData\Resolvers;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

trait InteractWithRequest
{
    /**
     * Holds instance of the request.
     *
     * @var Request|null $request
     */
    protected ?Request $request = null;

    /**
     * Check if the current method is 'store'.
     *
     * @return bool
     */
    protected function isStore(): bool
    {
        return $this->request()->isMethod('POST')
            && $this->currentRouteAction() === 'store';
    }

    /**
     * Check if the current method is 'update'
     *
     * @return bool
     */
    protected function isUpdate(): bool
    {
        return $this->request()->isMethod('PATCH')
            && $this->currentRouteAction() === 'update';
    }

    /**
     * Get the current route action.
     *
     * @return string
     */
    protected function currentRouteAction(): string
    {
        return Router::currentRouteAction();
    }

    /**
     * Get data type.
     *
     * @return string
     */
    protected function getType(): string
    {
        return $this->request()->input('type');
    }

    /**
     * Get data ID.
     *
     * @return string|null
     */
    protected function getId(): string|null
    {
        return $this->request()->input('id');
    }

    /**
     * Get the request object.
     *
     * @return Request
     */
    protected function request(): Request
    {
        if (is_null($this->request)) {
            $this->request = request();
        }

        return $this->request;
    }
}
