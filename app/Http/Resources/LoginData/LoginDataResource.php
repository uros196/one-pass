<?php

namespace App\Http\Resources\LoginData;

use App\Models\LoginData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LoginData */
class LoginDataResource extends JsonResource
{
    /**
     * Transform resource into an array.
     *
     * Used for populating a form.
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
