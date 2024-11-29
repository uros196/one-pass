<?php

namespace App\Http\Resources\NoteData;

use App\Models\NoteData;
use App\Services\SensitiveData\Router;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin NoteData */
class NoteDataListResource extends JsonResource
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
            'type' => Router::getTypeByModel(NoteData::class),
            'name' => $this->name,
            'color' => [
                'background_color' => $this->color->backgroundColor(),
                'text_color' => $this->color->fontColor()
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
