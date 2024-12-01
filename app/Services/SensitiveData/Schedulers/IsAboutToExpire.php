<?php

namespace App\Services\SensitiveData\Schedulers;

use App\Contracts\SensitiveData\ExpirableDataContract;
use App\Models\DataExpirationTime;
use App\Notifications\DataExpireSoonNotification;
use Illuminate\Support\Collection;

class IsAboutToExpire
{
    /**
     * Notify the users about data who expire soon.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->expiresSoon()->each(function (DataExpirationTime $item) {
            $item->sensitiveDataConnection->user->notify(
                new DataExpireSoonNotification($item->sensitiveDataConnection->connectable)
            );

            $item->forceFill(['is_notified' => true])->update();
        });
    }

    /**
     * Get the data that are about to expire soon.
     *
     * @return Collection<int, ExpirableDataContract>
     */
    protected function expiresSoon(): Collection
    {
        return DataExpirationTime::with('sensitiveDataConnection.user')
            ->with('sensitiveDataConnection.connectable')
            ->where('is_notified', false)
            ->whereBetween('expires_at', [
                now(),
                now()->addDays(config('sensitive_data.expire_soon'))
            ])
            ->get();
    }
}
