<?php

namespace App\Rules;

use Closure;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class VerifyMasterPasswordRule implements ValidationRule
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
        $attempt = Auth::attempt([
            'email'     => $this->request->user()->email,
            'password'  => $value
        ]);

        if (!$attempt) {
            // increase a failed login attempt
            RateLimiter::hit($this->throttleKey());
            $this->failedAttempt($fail);

            $fail(__('encryption.master_key.failed'));
        }
        else {
            RateLimiter::clear($this->throttleKey());
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @param Closure $fail
     * @return void
     */
    protected function failedAttempt(Closure $fail): void
    {
        $attempt = config('auth.password_confirmation_attempts');
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $attempt)) {
            return;
        }

        // market user's account as locked
        $this->request->user()->markAsLocked();

        event(new Lockout($this->request));

        // remove rate limiter when an account is locked
        // if the user reacts immediately, he can enter the password without waiting
        RateLimiter::clear($this->throttleKey());

        $fail(__('auth.locked'));
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->request->user()->email) .'|'. $this->request->ip() .'|master-password-validation'
        );
    }
}
