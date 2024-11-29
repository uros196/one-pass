<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SensitiveDataConnection extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'sensitive_data_connections';

    /**
     * Get the User associated with the model.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get the related sensitive data.
     *
     * @return MorphTo
     */
    public function data(): MorphTo
    {
        return $this->morphTo('connectable');
    }
}
