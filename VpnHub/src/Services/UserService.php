<?php

namespace App\Services;

use App\Core\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Validators\Web\LoginValidator;

class UserService
{
    public function login(Request $request): bool
    {
        try {
            $validator = new LoginValidator($request);

            if (!$validator) {
                //exibir erros
            }

            $user = new UserRepository();
            $user->findByUsername($request->input('username'));

            if (!$user) {
               //Usuário não encontrado
            }

            if (!password_verify($request->input('password'), $user->getPassword())) {
                 // Senha incorreta somar mais um no user_attemps
                return false; // Senha incorreta
            }

             return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}