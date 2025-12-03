<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'confirmed',
            Password::min(12)
                ->max(50)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
        ];
    }
}
