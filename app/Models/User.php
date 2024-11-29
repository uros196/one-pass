<?php

namespace App\Models;

use App\Models\Concerns\LockedAccount;
use App\Models\Concerns\HasEncryptionToken;
use App\Models\Traits\SensitiveDataConnections;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, LockedAccount, HasEncryptionToken;
    use SensitiveDataConnections;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_locked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get a related encryption key.
     *
     * @return HasOne
     */
    public function encryptionKey(): HasOne
    {
        return $this->hasOne(EncryptionKey::class);
    }

    /**
     * Get the current session relation.
     *
     * @return HasOne
     */
    public function session(): HasOne
    {
        return $this->hasOne(Session::class)
            ->where('id', session()->id());
    }

    /**
     * Get the all related session.
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class)->ordered();
    }

}
