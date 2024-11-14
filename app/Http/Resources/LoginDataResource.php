<?php

namespace App\Http\Resources;

use App\Models\LoginData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LoginData */
class LoginDataResource extends JsonResource
{
    /**
     * Transform resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'password' => $this->password,
            'url' => $this->url,
            'note' => $this->note,
            // TODO: add website favicon url
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
