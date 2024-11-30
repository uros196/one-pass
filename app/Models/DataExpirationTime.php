<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataExpirationTime extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sensitive_data_connection_id',
        'expires_at',
        'is_notified'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'date:Y-m-d',
        'is_notified' => 'boolean'
    ];

    /**
     * Get the related connection associated with the model.
     *
     * @return BelongsTo
     */
    public function sensitiveDataConnection(): BelongsTo
    {
        return $this->belongsTo(SensitiveDataConnection::class);
    }

    /**
     * Determine if the entity is expiring soon or not.
     *
     * @return Attribute
     */
    public function isExpiringSoon(): Attribute
    {
        return Attribute::get(function () {
            return now()->diffInDays($this->expires_at) <= config('sensitive_data.expire_soon');
        });
    }
}
