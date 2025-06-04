<?php

namespace App\Validators\Web;

use App\Core\Request;
use App\Validators\ValidatorInterface;

class LoginValidator implements ValidatorInterface
{
    protected array $messageBag = [];

    public function validate(Request $request): bool
    {
        if (empty($request->input('username'))) {
            $this->messageBag[] = 'Informe o username para realizar o processo de entrada';
        }

        if (empty($request->input('password'))) {
            $this->messageBag[] = 'Informe o password do usuário para realizar o processo de entrada';
        }

        if (count($this->messageBag) > 0) {
            return false;
        }

        return true;
    }

    public function messages() : array
    {
        return $this->messageBag;
    }
}