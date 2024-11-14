<?php

namespace App\Models;

use App\Casts\BasicEncryption;
use App\Casts\ChallengeEncryption;
use App\Models\Concerns\HasMorphedUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LoginData extends Model
{
    use HasUuids, HasMorphedUser;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'login_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'url',
        'note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'username' => BasicEncryption::class,
            'password' => ChallengeEncryption::class,
            'url' => BasicEncryption::class,
            'note' => BasicEncryption::class,
        ];
    }
}
