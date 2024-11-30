<?php

namespace App\Models\Concerns;

use App\Casts\BasicEncryption;
use App\Casts\ChallengeEncryption;
use App\Configs\ExpirableData\DocumentProvider;
use App\Contracts\SensitiveData\ExpirableNotificationContract;
use App\DataRegistrars\DocumentDataRegistrar;
use App\Enums\DocumentTypes;
use App\Http\Requests\SensitiveData\DocumentDataRequest;
use App\Http\Resources\DocumentData\DocumentDataListResource;
use App\Http\Resources\DocumentData\DocumentDataResource;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property DocumentTypes $types
 * @property string $name
 * @property string $number
 * @property string $place_of_issue
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
trait HasDocumentData
{
    use HasUuids, HasMorphedUser;

    /**
     * Define a global scope that applies condition for a certain document type that model represents.
     *
     * @return void
     */
    protected static function bootHasDocumentData(): void
    {
        static::addGlobalScope(function (Builder $builder) {
            $builder->where('type', self::documentType());
        });
    }

    /**
     * Define properties value.
     * Initialize method is something similar to constructor, it calls on creating new model instance.
     *
     * @return void
     */
    protected function initializeHasDocumentData(): void
    {
        // the table associated with the model
        $this->table = 'documents_data';

        // the attributes that are mass assignable
        $this->fillable = [
            'type',
            'name',
            'number',
            'issue_date',
            'expire_date',
            'place_of_issue',
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => DocumentTypes::class,
            'number' => ChallengeEncryption::class,
            'issue_date' => BasicEncryption::class,
            'expire_date' => BasicEncryption::class,
            'place_of_issue' => BasicEncryption::class
        ];
    }

    /**
     * Get date converted into an object.
     *
     * @return Attribute
     */
    protected function issueDate(): Attribute
    {
        return Attribute::get(function ($value) {
            return !is_null($value) ? Carbon::parse($value) : null;
        });
    }

    /**
     * Get date converted into an object.
     *
     * @return Attribute
     */
    protected function expireDate(): Attribute
    {
        return Attribute::get(function ($value) {
            return !is_null($value) ? Carbon::parse($value) : null;
        });
    }

    /**
     * Define the date when the data expires.
     * This date is important for informing an owner about a document expiring when the time comes.
     *
     * @return DateTime|string|null
     */
    public function dataExpiresAt(): DateTime|string|null
    {
        return $this->expire_date;
    }

    /**
     * Define an object that will provide the data for expiration notification.
     * Keep in mind that the data must be visible (unencrypted).
     *
     * @return ExpirableNotificationContract
     * @throws \Exception
     */
    public function expireNotificationDataProvider(): ExpirableNotificationContract
    {
        return new DocumentProvider($this);
    }

    /**
     * Define the list (or single) of the JsonResources.
     *
     * Available array keys: index, show, edit.
     * If you use an array, all keys must be present.
     *
     * @return string|array
     */
    public function dataResource(): string|array
    {
        return [
            'index' => DocumentDataListResource::class,
            'show' => '',
            'edit' => DocumentDataResource::class,
        ];
    }

    /**
     * Define the list (or single) of the FormRequests.
     *
     * Available array keys: store and update.
     * If you use an array, all keys must be present.
     *
     * @return string|array
     */
    public function dataFormRequest(): string|array
    {
        return DocumentDataRequest::class;
    }

    /**
     * Define the data registrar.
     *
     * @return string
     */
    public function dataRegistrar(): string
    {
        return DocumentDataRegistrar::class;
    }
}
