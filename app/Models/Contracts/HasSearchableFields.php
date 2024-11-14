<?php

namespace App\Models\Contracts;

use Illuminate\Http\Resources\Json\JsonResource;

interface HasSearchableFields
{
    /**
     * Define the list of the searchable fields (or a single field).
     *
     * @return array|string
     */
    public function fields(): array|string;

    /**
     * Define search response structure.
     *
     * @return JsonResource
     */
    public function searchResponse(): JsonResource;
}
