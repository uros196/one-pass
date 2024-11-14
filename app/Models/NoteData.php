<?php

namespace App\Models;

use App\Casts\ChallengeEncryption;
use App\Enums\CardColors;
use App\Models\Concerns\HasMorphedUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class NoteData extends Model
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
}
