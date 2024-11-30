<?php

namespace App\Http\Resources\BankCardData;

use App\Models\BankCardData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BankCardData */
class BankCardDataResource extends JsonResource
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
            'expire_date' => $this->expire_date?->format('Y-m'),
            'cvc' => $this->cvc,
            'pin' => $this->pin,
            'holder_name' => $this->holder_name,
            'type' => $this->type,
            'note' => $this->note,
        ];
    }
}
