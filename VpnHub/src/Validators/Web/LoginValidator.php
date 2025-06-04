<?php

namespace App\Validators\Web;

use App\Core\Request;
use App\Validators\WebValidatorInterface;

class LoginValidator implements WebValidatorInterface
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

        if (strlen($request->input('username')) < 3 || strlen($request->input('username')) > 30) {
            $this->messageBag[] = 'Verifique o seu usuário e tente novamente';
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