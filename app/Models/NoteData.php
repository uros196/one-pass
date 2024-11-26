<?php

namespace App\Models;

use App\Casts\ChallengeEncryption;
use App\DataRegistrars\NoteDataRegistrar;
use App\Enums\CardColors;
use App\Http\Requests\SensitiveData\NoteDataRequest;
use App\Http\Resources\NoteData\NoteDataListResource;
use App\Http\Resources\NoteData\NoteDataResource;
use App\Models\Concerns\HasMorphedUser;
use App\Models\Contracts\HasSensitiveData;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class NoteData extends Model implements HasSensitiveData
{
    use HasUuids, HasMorphedUser;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'notes_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'text',
        'color'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'text' => ChallengeEncryption::class,
            'color' => CardColors::class
        ];
    }

    /**
     * Define the list (or single) of the JsonResources.
     *
     * Available array keys: index, show, edit.
     * If you use an array, all keys must be present.
     *
     * @return string|array
     */
    public function dataResource(): string|array
    {
        return [
            'index' => NoteDataListResource::class,
            'show' => '',
            'edit' => NoteDataResource::class,
        ];
    }

    /**
     * Define the list (or single) of the FormRequests.
     *
     * Available array keys: store and update.
     * If you use an array, all keys must be present.
     *
     * @return string|array
     */
    public function dataFormRequest(): string|array
    {
        return NoteDataRequest::class;
    }

    /**
     * Define the data registrar.
     *
     * @return string
     */
    public function dataRegistrar(): string
    {
        return NoteDataRegistrar::class;
    }

}
