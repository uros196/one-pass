<?php

namespace App\Contracts\Models;

use App\Enums\DocumentTypes;

interface DocumentDataContract
{
    /**
     * Define for a witch document type model is responsible.
     *
     * @return DocumentTypes
     */
    public static function documentType(): DocumentTypes;

    /**
     * Define relation name on the User model.
     *
     * @return string
     */
    public function relationName(): string;
}
