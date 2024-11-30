<?php

namespace App\Http\Resources\BankCardData;

use App\Models\BankCardData;
use App\Services\SensitiveData\Router;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BankCardData */
class BankCardDataListResource extends JsonResource
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
            'type' => Router::getTypeByModel(BankCardData::class),
            'name' => $this->name,
            'number' => $this->card_hidden_number,
            'expire_date' => $this->expire_date?->format('m/y'),
            'holder_name' => $this->holder_name,
            'is_expiring_soon' => $this->dataConnection->dataExpirationDate->is_expiring_soon

            // TODO: card type and card image based on the type
        ];
    }
}
