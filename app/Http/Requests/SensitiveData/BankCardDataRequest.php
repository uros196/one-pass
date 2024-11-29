<?php

namespace App\Http\Requests\SensitiveData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BankCardDataRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'number' => 'required|numeric|digits:16',
            'expire_date' => 'nullable|date_format:m/y|after_or_equal:today',
            'cvc' => 'nullable|numeric|digits_between:3,4',
            'pin' => 'nullable|numeric|digits:4',
            'holder_name' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // remove all extra whitespace
        $this->merge([
            'number' => Str::squish(str_replace(' ', '', $this->input('number')))
        ]);
    }

}
