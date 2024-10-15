<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    /**
     * Get related User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order session by last activity.
     *
     * @param Builder $builder
     * @return void
     */
    public function scopeOrdered(Builder $builder): void
    {
        $builder->orderBy('last_activity', 'desc');
    }
}
