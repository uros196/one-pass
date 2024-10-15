<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

/**
 * Security check.
 * Verify that user makes request from its own session if 'Encryption Token' has been exposed.
 */
class VerifyUserRequestRule implements ValidationRule
{
    /**
     * VerifyMasterPasswordRule constructor.
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
        $session = $this->request->user()->session()
            ->where('ip_address', $this->request->ip())
            ->first();

        if (!$session) {
            $fail(__('encryption.token.session_failed'));
        }
    }
}
