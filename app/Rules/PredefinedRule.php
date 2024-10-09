<?php

namespace App\Rules;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PredefinedRule
{
    /**
     * List of the predefined validation rules for 'password' validation.
     *
     * @return array
     */
    public static function password(): array
    {
        return [
            'required',
            'confirmed',
            Password::defaults()
        ];
    }

    /**
     * List of the predefined validation rules for 'email' validation.
     *
     * @param string $model
     * @param string $column - column for unique validation
     *
     * @return array
     */
    public static function email(string $model, string $column = 'NULL'): array
    {
        return [
            'required', 'string', 'lowercase', 'email', 'max:255',
            Rule::unique($model, $column)
        ];
    }
}
