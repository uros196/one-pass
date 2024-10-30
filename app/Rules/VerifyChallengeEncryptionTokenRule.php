<?php

namespace App\Rules;

use App\Models\EncryptionToken;
use App\Services\Encryption\Challenge\TokenFactory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class VerifyChallengeEncryptionTokenRule implements ValidationRule
{
    /**
     * VerifyEncryptionTokenRule constructor.
     *
     * @param Request $request
     */
    public function __construct(protected Request $request) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!($encryptedToken = $this->getEncryptedToken())) {
            $fail(__('encryption.token.not_found'));
            return;
        }

        if (!TokenFactory::verify($encryptedToken, $value)) {
            // invalid token is submitted
            $fail(__('encryption.token.failed'));
            return;
        }

        if (TokenFactory::isExpired($encryptedToken)) {
            // if token ends his short life, delete it from the DB
            $encryptedToken->delete();
            $fail(__('encryption.token.expired'));
        }
    }

    /**
     * Get the encrypted token from the DB.
     *
     * @return EncryptionToken|null
     */
    protected function getEncryptedToken(): ?EncryptionToken
    {
        // reload 'encryptionToken' relation
        $user = $this->request->user()->load('encryptionToken');

        return $user->encryptionToken;
    }
}
