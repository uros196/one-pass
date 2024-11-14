<?php

namespace App\Http\Resources\NoteData;

use App\Models\NoteData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin NoteData */
class NoteDataResource extends JsonResource
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
            'text' => $this->text,
            'color' => $this->color->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
