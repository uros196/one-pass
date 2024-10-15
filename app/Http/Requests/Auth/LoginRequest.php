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
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        if (User::accountBlocked($this->get('email'))) {
            $this->failed(__('auth.blocked'));
        }

        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {

            // increase a failed login attempt
            RateLimiter::hit($this->throttleKey());
            $this->failedAttempt();

            $this->failed(__('auth.failed'));
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function failedAttempt(): void
    {
        $attempt = config('auth.password_confirmation_attempts');
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $attempt)) {
            return;
        }

        // market user's account as blocked
        $is_blocked = User::where('email', $this->get('email'))->first()?->markAsBlocked();

        if ($is_blocked) {
            event(new Lockout($this));

            $this->failed(__('auth.blocked'));
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
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) .'|'. $this->ip());
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
            'email' => $message,
        ]);
    }
}
