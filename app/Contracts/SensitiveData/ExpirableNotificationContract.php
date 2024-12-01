<?php

namespace App\Contracts\SensitiveData;

interface ExpirableNotificationContract
{
    /**
     * Define an icon that will be displayed.
     *
     * @return string
     */
    public function icon(): string;

    /**
     * Define a name that will be displayed.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Define the edit/show link that will be routed to.
     *
     * @return string
     */
    public function link(): string;
}
