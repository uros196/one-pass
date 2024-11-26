<?php

namespace App\Models\Contracts;

interface HasSensitiveData
{
    /**
     * Define the list (or single) of the JsonResources.
     *
     * Available array keys: index, show, edit.
     * If you use an array, all keys must be present.
     *
     * @return string|array
     */
    public function dataResource(): string|array;

    /**
     * Define the list (or single) of the FormRequests.
     *
     * Available array keys: store and update.
     * If you use an array, all keys must be present.
     *
     * @return string|array
     */
    public function dataFormRequest(): string|array;

    /**
     * Define the data registrar.
     *
     * @return string
     */
    public function dataRegistrar(): string;
}
