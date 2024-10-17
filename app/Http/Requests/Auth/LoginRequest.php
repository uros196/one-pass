<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * After validation passed, try to authenticate the user.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        $this->authenticate();
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        if ($this->isAccountLocked() && !$this->proceedEvenIfLocked()) {
            $this->failed(__('auth.locked'));
        }

        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {

            // increase a failed login attempt
            RateLimiter::hit($this->throttleKey());
            $this->failedAttempt();

            $this->failed(__('auth.failed'));
        }

        RateLimiter::clear($this->throttleKey());

        $this->regenerateSession();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    protected function failedAttempt(): void
    {
        $attempt = config('auth.password_confirmation_attempts');
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $attempt)) {
            return;
        }

        if (
            // if the account is already lock, do not take any action
            !$this->isAccountLocked()
            // market user's account as locked
            && User::where('email', $this->validated('email'))->first()?->markAsLocked()
        ) {
            event(new Lockout($this));

            // remove rate limiter when an account is locked
            // if the user reacts immediately, he can confirm the password without waiting
            RateLimiter::clear($this->throttleKey());

            $this->failed(__('auth.locked'));
        }
        // this is a case if email does not exist in our system
        else {
            $seconds = RateLimiter::availableIn($this->throttleKey());

            $this->failed(
                trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            );
        }

    }

    /**
     * Regenerate the session.
     *
     * @return void
     */
    protected function regenerateSession(): void
    {
        $this->session()->regenerate();

        // mark that password is confirmed so the user can access to sensitive part of the application immediately
        $this->session()->put('auth.password_confirmed_at', time());
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) .'|'. $this->ip());
    }

    /**
     * Check if the account is locked.
     *
     * @return bool
     */
    protected function isAccountLocked(): bool
    {
        return User::isAccountLocked($this->validated('email'));
    }

    /**
     * Indicates that we need to proceed to verification process event if the account is locked.
     * (default: if the account is locked, we immediately stop the verification and show the error message)
     *
     * @return bool
     */
    protected function proceedEvenIfLocked(): bool
    {
        return false;
    }

    /**
     * Inform about failed validation.
     *
     * @param string $message
     * @return void
     *
     * @throws ValidationException
     */
    protected function failed(string $message): void
    {
        throw ValidationException::withMessages([
            $this->validationFieldName() => $message,
        ]);
    }

    /**
     * Define a name to attach a validation error message to it.
     *
     * @return string
     */
    protected function validationFieldName(): string
    {
        return 'email';
    }
}
