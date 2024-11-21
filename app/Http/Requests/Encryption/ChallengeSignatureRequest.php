<?php

namespace App\Http\Requests\Encryption;

use App\Rules\VerifyChallengeEncryptionTokenRule;
use App\Rules\VerifyMasterPasswordRule;
use App\Services\Encryption\Challenge\TokenFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ExcludeIf;

/**
 * This request is using for validating 'Master Password' or validating
 * token that will decrypt 'Challenge Signature'.
 *
 * The 'Master Password' is a part of the 'Challenge Signature', and 'Challenge Signature' is used
 * alongside 'Encryption Public Key' for making a final 'Encryption Key'.
 * 'Encryption Key' is the most important part of the 'sensitive data encrypting' system.
 * It is used as a key while encrypting/decrypting user's sensitive data.
 *
 * This request validates data needs for a challenge encryption system.
 */
class ChallengeSignatureRequest extends FormRequest
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
            'validate_all' => 'boolean',

            // required while generating encryption token
            'master_password' => [
                // this field will be excluded from the 'validate' and 'validated'
                // if 'encryption_token' is present in the request and validate ALL is not requested
                $this->excludeWith('encryption_token'),
                'required',
                new VerifyMasterPasswordRule($this)
            ],

            // required while decrypting a Challenge Signature
            'encryption_token' => [
                // this field will be excluded from the 'validate' and 'validated'
                // if 'master_password' is present in the request and validate ALL is not requested
                $this->excludeWith('master_password'),
                'required',
                new VerifyChallengeEncryptionTokenRule($this)
            ],
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

        // this request will validate only one attribute,
        // but there is a need to validate both fields in a certain situation
        // if you want that, your request should have 'validate_all' attribute set to 'true'
        if (!$this->has('validate_all')) {
            $this->merge([
                // default value if such an attribute is not present in the request
                'validate_all' => false
            ]);
        }
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        // if token pass the validation, extend its life
        if ($this->validated('encryption_token', false)) {
            TokenFactory::extendTokenLife($this->user()->encryptionToken);
        }
    }

    /**
     * Determine if the attribute under the validation needs to be excluded from it.
     *
     * @param string $attribute
     * @return ExcludeIf
     */
    protected function excludeWith(string $attribute): ExcludeIf
    {
        return Rule::excludeIf(function () use ($attribute) {
            return $this->has($attribute) && !$this->boolean('validate_all');
        });
    }
}
