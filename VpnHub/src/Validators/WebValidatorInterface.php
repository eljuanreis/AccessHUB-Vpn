<?php

namespace App\Validators;

use App\Core\Request;

interface WebValidatorInterface
{
    public function validate(Request $request): bool;
    public function messages(): array;
}
