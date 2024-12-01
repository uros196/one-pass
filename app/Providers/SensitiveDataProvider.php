<?php

namespace App\Providers;

use App\Contracts\SensitiveData\Resolvers\DataFormRequest;
use App\Contracts\SensitiveData\Resolvers\DataModel;
use App\Contracts\SensitiveData\Resolvers\DataRegistrar;
use App\Contracts\SensitiveData\Resolvers\DataResource;
use App\Services\SensitiveData\Router;
use Illuminate\Support\ServiceProvider;

class SensitiveDataProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(DataFormRequest::class, fn () => Router::resolveFormRequest());
        $this->app->bind(DataRegistrar::class, fn () => Router::resolveRegistrar());
        $this->app->bind(DataModel::class, fn () => Router::resolveModel());
        $this->app->bind(DataResource::class, fn () => Router::resolveResource());
    }
}
