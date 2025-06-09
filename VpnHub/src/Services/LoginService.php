<?php

namespace App\Services;

use App\Core\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Mail;
use App\Validators\Web\LoginValidator;
use App\Validators\Web\SendResetPasswordLinkValidator;

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

    public function sendResetPassword(Request $request)
    {
        $validator = new SendResetPasswordLinkValidator($request);

        if (!$validator) {
            $this->messageBag = $validator->messages();

            return false;
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findByUsername($request->input('username'));

        if (!$user) {
            return true;
        }

        $token = bin2hex(random_bytes(32));
        $user->setResetToken($token);
        $user->setResetTokenCreatedAt(new \DateTime());
        $userRepository->save($user);

        $resetLink = sprintf('%s/password-reset?token=%s', $_ENV['APP_URL'] ?? 'http://localhost', $token);

        try {
            Mail::send(
                'contatojuanzito@gmail.com',
                'Recuperação de Senha',
                "Clique no link para redefinir sua senha: $resetLink"
            );
        } catch (\Throwable $th) {
            throw $th;
        }


        return true;
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
