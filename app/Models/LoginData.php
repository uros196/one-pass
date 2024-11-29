<?php

namespace App\Models;

use App\Casts\BasicEncryption;
use App\Casts\ChallengeEncryption;
use App\Contracts\Models\HasSensitiveData;
use App\DataRegistrars\LoginDataRegistrar;
use App\Http\Requests\SensitiveData\LoginDataRequest;
use App\Http\Resources\LoginData\LoginDataListResource;
use App\Http\Resources\LoginData\LoginDataResource;
use App\Models\Concerns\HasMorphedUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LoginData extends Model implements HasSensitiveData
{
    use HasUuids, HasMorphedUser;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'logins_data';

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
            'index' => LoginDataListResource::class,
            'show' => '',
            'edit' => LoginDataResource::class,
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
        return LoginDataRequest::class;
    }

    /**
     * Define the data registrar.
     *
     * @return string
     */
    public function dataRegistrar(): string
    {
        return LoginDataRegistrar::class;
    }
}
