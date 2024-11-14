<?php

namespace App\Http\Resources\LoginData;

use App\Models\LoginData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LoginData */
class LoginDataListResource extends JsonResource
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
            'type' => LoginData::class,
            'name' => $this->name,
            'username' => $this->username,
            'url' => $this->url,
            // TODO: add website favicon url
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
