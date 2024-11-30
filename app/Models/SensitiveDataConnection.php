<?php

namespace App\Models;

use App\Contracts\SensitiveData\ExpirableDataContract;
use App\Services\SensitiveData\WatchExpirationTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    public function data(): MorphTo
    {
        return $this->morphTo('connectable');
    }

    /**
     * Get the related expiration time associated with the model.
     *
     * @return BelongsTo
     */
    public function dataExpirationDate(): BelongsTo
    {
        return $this->belongsTo(DataExpirationTime::class);
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
            if ($model->data instanceof ExpirableDataContract) {
                $watch = app(WatchExpirationTime::class);
                $watch($model->data);
            }

        });
    }
}
