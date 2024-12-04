<?php

namespace App\DataRegistrars;

use App\Contracts\SensitiveData\Resolvers\DataRegistrar;
use App\Enums\BankCardTypes;
use App\Models\BankCardData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Jlorente\Laravel\CreditCards\Facades\CreditCardValidator;

class BankCardDataRegistrar implements DataRegistrar
{
    /**
     * Store Bank Card data into DB.
     *
     * @param FormRequest $request
     * @return Model
     */
    public function store(FormRequest $request): Model
    {
        $bankCard = BankCardData::create([
            ...$request->validated(),
            'type'          => $this->getCardType($request),
            'number_length' => $this->getCardNumberLength($request),
            'identifier'    => $this->getIdentifier($request),
        ]);
        auth()->user()->bankCardData()->attach($bankCard);

        return $bankCard;
    }

    /**
     * Update existing Bank Card data.
     *
     * @param FormRequest $request
     * @param Model $model
     *
     * @return void
     */
    public function update(FormRequest $request, Model $model): void
    {
        $model->update([
            ...$request->validated(),
            'type'          => $this->getCardType($request),
            'number_length' => $this->getCardNumberLength($request),
            'identifier'    => $this->getIdentifier($request),
        ]);
    }

    /**
     * Get the list of the user Bank Card data.
     *
     * @return Collection|LengthAwarePaginator
     */
    public function list(): Collection|LengthAwarePaginator
    {
        return auth()->user()->bankCardData
            // load relation that handles expiration date
            ->load('dataConnection.dataExpirationDate');
    }

    /**
     * Get the card type based on the card number.
     *
     * @param FormRequest $request
     * @return BankCardTypes
     */
    protected function getCardType(FormRequest $request): BankCardTypes
    {
        $type = CreditCardValidator::getType($request->validated('number'));

        return BankCardTypes::tryFrom($type?->getType() ?? BankCardTypes::NONE->value);
    }

    /**
     * Get the card number length so we can later properly display the hidden card number.
     *
     * @param FormRequest $request
     * @return int
     */
    protected function getCardNumberLength(FormRequest $request): int
    {
        return strlen((string)$request->validated('number'));
    }

    /**
     * Get the card identified (last four digits).
     *
     * @param FormRequest $request
     * @return string
     */
    protected function getIdentifier(FormRequest $request): string
    {
        return substr($request->input('number'), -4);
    }
}
