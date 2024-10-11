<?php

namespace App\Http\Requests\Encryption;

use App\Rules\VerifyEncryptionTokenRule;
use App\Rules\VerifyMasterPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * This request is using for validating 'Master Password' or validating
 * token that will decrypt 'Master Key'.
 *
 * The 'Master Password' is a part of the 'Master Key', and 'Master Key' is using for making 'Encryption Key'.
 * 'Encryption Key' is the most important part of the 'sensitive data encrypting' system.
 * It is used as a key while encrypting/decrypting user's sensitive data.
 */
class MasterKeyRequest extends FormRequest
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
            // required while generating encryption token
            'password' => [
                // this field will be excluded from the 'validate' and 'validated'
                // if 'encryption_token' is present in the request
                'exclude_with:encryption_token',
                'required',
                new VerifyMasterPasswordRule($this)
            ],

            // required while decrypting a Master Key
            'encryption_token' => [
                // this field will be excluded from the 'validate' and 'validated'
                // if 'password' is present in the request
                'exclude_with:password',
                'required',
                new VerifyEncryptionTokenRule($this)
            ]
        ];
    }

    /**
     * Prepare data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // get the encryption token from the header (if exist) and merge if
        // with request for further validation
        // is not required to send token via header (it is optional)
        if ($token = $this->header('X-ENCRYPT-TOKEN')) {
            $this->merge([
                'encryption_token' => $token
            ]);
        }
    }
}
