<?php

namespace App\Models;

use App\Contracts\Models\DocumentDataContract;
use App\Contracts\Models\HasSensitiveData;
use App\Enums\DocumentTypes;
use App\Models\Concerns\HasDocumentData;
use Illuminate\Database\Eloquent\Model;

class IdCardDocument extends Model implements HasSensitiveData, DocumentDataContract
{
    use HasDocumentData;

    /**
     * Define for a witch document type model is responsible.
     *
     * @return DocumentTypes
     */
    public static function documentType(): DocumentTypes
    {
        return DocumentTypes::ID_CARD;
    }

    /**
     * Define relation name on the User model.
     *
     * @return string
     */
    public function relationName(): string
    {
        return 'idCardData';
    }
}
