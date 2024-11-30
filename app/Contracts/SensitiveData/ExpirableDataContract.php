<?php

namespace App\Contracts\SensitiveData;

use App\Models\SensitiveDataConnection;
use DateTime;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property SensitiveDataConnection $dataConnection
 */
interface ExpirableDataContract
{
    /**
     * Define the date when the data expires.
     *
     * @return DateTime|string|null
     */
    public function dataExpiresAt(): DateTime|string|null;

    /**
     * Define an object that will provide the data for expiration notification.
     * Keep in mind that the data must be visible (unencrypted).
     *
     * @return ExpirableNotificationContract
     */
    public function expireNotificationDataProvider(): ExpirableNotificationContract;

    /**
     * Define a relation to the data connection model.
     *
     * @return MorphOne
     */
    public function dataConnection(): MorphOne;
}
