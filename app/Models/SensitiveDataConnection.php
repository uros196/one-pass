<?php

namespace App\Models;

use App\Contracts\SensitiveData\ExpirableDataContract;
use App\Services\SensitiveData\WatchExpirationTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SensitiveDataConnection extends MorphPivot
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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related sensitive data.
     *
     * @return MorphTo
     */
    public function connectable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the related expiration time associated with the model.
     *
     * @return HasOne
     */
    public function dataExpirationDate(): HasOne
    {
        return $this->hasOne(DataExpirationTime::class, 'sensitive_data_connection_id');
    }

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted(): void
    {
        // listen model 'created' event
        self::created(function (SensitiveDataConnection $model) {

            // if the data model implements 'ExpirableDataContract', it means that it has
            // the data that can expire, and we must remember that so we can inform the user when time comes
            if ($model->connectable instanceof ExpirableDataContract) {
                $watch = app(WatchExpirationTime::class);
                $watch($model->connectable);
            }

        });
    }
}
