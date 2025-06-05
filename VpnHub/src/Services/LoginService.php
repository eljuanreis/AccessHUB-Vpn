<?php

namespace App\Services;

use App\Core\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Validators\Web\LoginValidator;

class LoginService
{
    const LOGIN_LIMIT_ATTEMPTS = 10;

    protected array $messageBag = [];
    protected User $user;

    public function login(Request $request): bool
    {
        try {
            $validator = new LoginValidator($request);

            if (!$validator) {
                $this->messageBag = $validator->messages();

                return false;
            }

            $userRepository = new UserRepository();
            $user = $userRepository->findByUsername($request->input('username'));

            if (!$user) {
                $this->messageBag[] = 'Usuário ou senha estão incorretos';

                return false;
            }

            if ($user->getLoginAttempts() >= self::LOGIN_LIMIT_ATTEMPTS) {
                $this->messageBag[] = 'Entre em contato com o administrador da rede';

                return false;
            }

            if (!password_verify($request->input('password'), $user->getPassword())) {
                 // Senha incorreta somar mais um no user_attemps
                $this->messageBag[] = 'Usuário ou senha estão incorretos';

                $user->incrementLoginAttempts();

                $userRepository->save($user);

                return false;
            }

            $this->user = $user;

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getUser()
    {
        return $this->user;
    }

    public function messages(): array
    {
        return $this->messageBag;
    }
}
