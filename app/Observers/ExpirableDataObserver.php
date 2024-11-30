<?php

namespace App\Observers;

use App\Contracts\SensitiveData\ExpirableDataContract;
use App\Services\SensitiveData\WatchExpirationTime;

class ExpirableDataObserver
{
    /**
     * Remember data expiration time so we can inform the user when that
     * date comes close.
     *
     * @param ExpirableDataContract $model
     * @return void
     */
    public function updated(ExpirableDataContract $model): void
    {
        $watch = app(WatchExpirationTime::class);
        $watch($model);
    }
}
