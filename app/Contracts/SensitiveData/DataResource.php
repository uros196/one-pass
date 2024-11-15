<?php

namespace App\Contracts\SensitiveData;

use Illuminate\Http\Resources\Json\JsonResource;

interface DataResource
{
    /**
     * Get the bound JsonResource.
     *
     * @return JsonResource
     */
    public function get(): JsonResource;
}
