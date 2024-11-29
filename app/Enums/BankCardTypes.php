<?php

namespace App\Enums;

use Jlorente\CreditCards\CreditCardTypeConfig;
use Jlorente\Laravel\CreditCards\Facades\CreditCardValidator;

enum BankCardTypes: string
{
    case NONE = 'none';
    case VISA = 'visa';
    case MASTERCARD = 'mastercard';
    case AMERICAN_EXPRESS = 'american-express';
    case DINERS_CLUB = 'diners-club';
    case DISCOVER = 'discover';
    case JCB = 'jcb';
    case UNIONPAY = 'unionpay';
    case MAESTRO = 'maestro';
    case ELO = 'elo';
    case MIR = 'mir';
    case HIPER = 'hiper';
    case HIPERCARD = 'hipercard';
    case TROY = 'troy';
    case CABAL = 'cabal';

    /**
     * Get the credit card nice name based on its type.
     *
     * @return string|null
     */
    public function niceName(): ?string
    {
        return $this->config()?->getNiceType();
    }

    /**
     * Get the credit card config based on its type.
     *
     * @return CreditCardTypeConfig|null
     */
    public function config(): ?CreditCardTypeConfig
    {
        return CreditCardValidator::getTypeInfo($this->value);
    }
}
