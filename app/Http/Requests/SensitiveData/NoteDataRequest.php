<?php

namespace App\Http\Requests\SensitiveData;

use App\Enums\CardColors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoteDataRequest extends FormRequest
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
            'text' => 'nullable|string|max:255',
            'color' => ['nullable', 'integer', Rule::enum(CardColors::class)]
        ];
    }
}
