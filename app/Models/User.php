<?php

namespace App\Models;

use App\Models\Concerns\HasEncryptionToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, HasEncryptionToken;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_blocked',
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
     * Get related encryption key.
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

    /**
     * Append 'is_blocked => true' condition.
     *
     * @param Builder $builder
     * @return void
     */
    public function scopeBlocked(Builder $builder): void
    {
        $builder->where('is_blocked', true);
    }

    /**
     * Check if the account is blocked.
     *
     * @param Builder $builder
     * @param string $email
     *
     * @return bool
     */
    public function scopeAccountBlocked(Builder $builder, string $email): bool
    {
        return $builder->where('email', $email)->first()?->is_blocked ?? false;
    }

    /**
     * Mark the account as blocked.
     *
     * @return bool
     */
    public function markAsBlocked(): bool
    {
        return $this->forceFill(['is_blocked' => true])->save();
    }
}
