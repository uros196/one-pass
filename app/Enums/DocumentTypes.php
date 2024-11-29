<?php

namespace App\Enums;

use App\Models\DriverLicenseDocument;
use App\Models\IdCardDocument;
use App\Models\PassportDocument;
use App\Services\SensitiveData\Router;

enum DocumentTypes: string
{
    case ID_CARD = 'ID_CARD';
    case PASSPORT = 'PASSPORT';
    case DRIVER_LICENSE = 'DRIVER_LICENSE';
}
