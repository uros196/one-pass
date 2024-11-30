<?php

namespace App\Http\Resources\DocumentData;

use App\Models\IdCardDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin IdCardDocument */
class DocumentDataResource extends JsonResource
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
            'name' => $this->name,
            'number' => $this->number,
            'issue_date' => $this->issue_date?->format('d/m/Y'),
            'expire_date' => $this->expire_date?->format('d/m/Y'),
            'place_of_issue' => $this->place_of_issue
        ];
    }
}
