<?php

namespace App\Http\Resources\DocumentData;

use App\Models\IdCardDocument;
use App\Services\SensitiveData\Router;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin IdCardDocument */
class DocumentDataListResource extends JsonResource
{
    /**
     * Transform resource into an array.
     *
     * Used for listing.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => Router::getTypeByModel(get_class($this->resource)),
            'name' => $this->name,
        ];
    }
}
