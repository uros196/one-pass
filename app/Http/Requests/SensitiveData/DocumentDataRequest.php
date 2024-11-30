<?php

namespace App\Http\Requests\SensitiveData;

use Illuminate\Foundation\Http\FormRequest;

class DocumentDataRequest extends FormRequest
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
            'number' => 'nullable|numeric',
            'issue_date' => 'nullable|date|before_or_equal:today',
            'expire_date' => 'nullable|date|after_or_equal:today',
            'place_of_issue' => 'nullable|string|max:255',
        ];
    }

}
