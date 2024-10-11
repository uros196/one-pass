<?php

namespace App\Rules;

use App\Models\EncryptionToken;
use App\Services\Encryption\Token\TokenFactory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * HTTP error status codes:
 *      400 - invalid token is submitted. User flow - new token needs to be created
 *      404 - token is not created at all (for this user and session)
 *      419 - token is expired, and user needs to create a new one
 */
class VerifyEncryptionTokenRule implements ValidationRule
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
        $encryptedToken = $this->getEncryptedToken();

        if (!TokenFactory::verify($encryptedToken, $value)) {
            // invalid token is submitted
            abort(Response::HTTP_BAD_REQUEST, __('encryption.token.failed'));
        }

        if (TokenFactory::isExpired($encryptedToken)) {
            // if token ends his short life, delete it from the DB
            $encryptedToken->delete();
            abort(419, __('encryption.token.expired'));
        }

        // if token verification is passed, extend token life
        $encryptedToken->forceFill([
            'last_used_at' => now(),
            'expires_at'   => now()->addMinutes(config('auth.encryption_token.expire')),
        ])->save();
    }

    /**
     * Get the encrypted token from the DB.
     *
     * @return EncryptionToken
     */
    protected function getEncryptedToken(): EncryptionToken
    {
        // reload 'encryptionToken' relation
        $user = $this->request->user()->load('encryptionToken');

        if (empty($user->encryptionToken)) {
            abort(Response::HTTP_NOT_FOUND, __('encryption.token.not_found'));
        }

        return $user->encryptionToken;
    }
}
