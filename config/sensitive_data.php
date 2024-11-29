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

        'driver-license' => [
            'model' => \App\Models\DriverLicenseDocument::class,
        ],
    ]
];
