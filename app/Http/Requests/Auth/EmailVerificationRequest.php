<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest as FormRequest;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // exclude ID from the URL
        //
        // if (! hash_equals((string) $this->user()->getKey(), (string) $this->route('id'))) {
        //     return false;
        // }

        if (! hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }
}
