<?php

namespace App\Services\SensitiveData;

use App\Contracts\SensitiveData\ExpirableDataContract;
use Illuminate\Support\Carbon;

class WatchExpirationTime
{
    /**
     * Remember data expiration time so we can inform the user when that
     * date comes close.
     *
     * @param ExpirableDataContract $model
     * @return void
     */
    public function __invoke(ExpirableDataContract $model): void
    {
        $date = $model->dataExpiresAt();

        // if the user is not provided to us expiration date,
        // such a date will be skipped
        if (is_null($date)) {
            return;
        }

        // if the date is provided as string, it will be transformed into DateTime
        // so we can easily adapt the date format we need
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $model->dataConnection->dataExpirationDate()->updateOrCreate([], [
            'expires_at' => $date,
        ]);
    }

}
