<?php

return [

    /*
     * All available sensitive data type definition with their related models.
     * Only types defined here can be accessible. That means it be edited,
     * created, listed and deleted using app interface
     */
    'connections' => [
        'login' => [
            'model' => \App\Models\LoginData::class,
        ],

        'note' => [
            'model' => \App\Models\NoteData::class,
        ],

        'bank-card' => [
            'model' => \App\Models\BankCardData::class,
        ],

        'id-card' => [
            'model' => \App\Models\IdCardDocument::class,
        ],

        'passport' => [
            'model' => \App\Models\PassportDocument::class,
        ],

        'driver-license' => [
            'model' => \App\Models\DriverLicenseDocument::class,
        ],
    ],

    // notify the use if its data is about to expire soon
    // the value is expressed in days
    'expire_soon' => 30
];
