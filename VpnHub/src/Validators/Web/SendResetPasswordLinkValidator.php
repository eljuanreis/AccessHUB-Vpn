<?php

namespace App\Validators\Web;

use App\Core\Request;
use App\Validators\WebValidatorInterface;

class SendResetPasswordLinkValidator implements WebValidatorInterface
{
    protected array $messageBag = [];

    public function validate(Request $request): bool
    {
        if (strlen($request->input('username')) < 3 || strlen($request->input('username')) > 30) {
            $this->messageBag[] = 'Informe o username para realizar o processo de entrada';
        }

        if (count($this->messageBag) > 0) {
            return false;
        }

        return true;
    }

    public function messages(): array
    {
        return $this->messageBag;
    }
}
