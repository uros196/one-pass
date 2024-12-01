<?php

namespace App\Contracts\SensitiveData\Resolvers;

use Illuminate\Foundation\Http\FormRequest;

interface DataFormRequest
{
    /**
     * Get the bound FormRequest.
     *
     * @return FormRequest
     */
    public function get(): FormRequest;
}
