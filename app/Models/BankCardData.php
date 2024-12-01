<?php

namespace App\Models;

use App\Casts\BasicEncryption;
use App\Casts\ChallengeEncryption;
use App\Configs\ExpirableData\BankCardProvider;
use App\Contracts\Models\HasSensitiveData;
use App\Contracts\SensitiveData\ExpirableDataContract;
use App\Contracts\SensitiveData\ExpirableNotificationContract;
use App\DataRegistrars\BankCardDataRegistrar;
use App\Enums\BankCardTypes;
use App\Http\Requests\SensitiveData\BankCardDataRequest;
use App\Http\Resources\BankCardData\BankCardDataListResource;
use App\Http\Resources\BankCardData\BankCardDataResource;
use App\Models\Concerns\HasMorphedUser;
use App\Observers\ExpirableDataObserver;
use DateTime;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property DateTime|null $expire_date
 */
#[ObservedBy(ExpirableDataObserver::class)]
class BankCardData extends Model implements HasSensitiveData, ExpirableDataContract
{
    use HasUuids, HasMorphedUser;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'bank_cards_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'number',
        'number_length',
        'identifier',
        'expire_date',
        'cvc',
        'pin',
        'holder_name',
        'type',
        'note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'number'        => ChallengeEncryption::class,
            'cvc'           => ChallengeEncryption::class,
            'pin'           => ChallengeEncryption::class,
            'note'          => ChallengeEncryption::class,
            'expire_date'   => BasicEncryption::after('date:m/y'),
            'holder_name'   => BasicEncryption::class,
            'type'          => BankCardTypes::class,
        ];
    }

    /**
     * Get the card hidden number.
     *
     * @return Attribute
     */
    public function cardHiddenNumber(): Attribute
    {
        return Attribute::get(function () {
            $identifier_length = strlen((string)$this->identifier);
            $hidden = str_repeat('•', ($this->number_length - $identifier_length));

            // try to match a card number format with a card type
            return $this->type->config()?->format("{$hidden}{$this->identifier}")
                // if type is NONE, use a default format
                ?? "•••• •••• •••• $this->identifier";
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
        // because we remember only the month and year of the expiration date,
        // we need to modify such a date by adding the last date of the current month
        return $this->expire_date?->endOfMonth();
    }

    /**
     * Define an object that will provide the data for expiration notification.
     * Keep in mind that the data must be visible (unencrypted).
     *
     * @return ExpirableNotificationContract
     */
    public function expireNotificationDataProvider(): ExpirableNotificationContract
    {
        return new BankCardProvider($this);
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
            'index' => BankCardDataListResource::class,
            'show' => '',
            'edit' => BankCardDataResource::class,
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
        return BankCardDataRequest::class;
    }

    /**
     * Define the data registrar.
     *
     * @return string
     */
    public function dataRegistrar(): string
    {
        return BankCardDataRegistrar::class;
    }

}
