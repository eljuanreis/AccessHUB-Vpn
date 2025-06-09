<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Repository\UserRepository;
use App\Services\LoginService;
use App\Utils\Route;
use App\Utils\Session;

class AuthController
{
    public function show()
    {
        return View::make('authentication/login');
    }

    public function login(Request $request)
    {
        $loginService = new LoginService();

        if (!$loginService->login($request)) {
            Session::put(Session::FLASH, 'errors', $loginService->messages());

            return Route::redirect('GET', '/login');
        }

        $user = $loginService->getUser();
        Session::put(Session::USER, 'user', $user);
        Session::put(Session::FLASH, 'success', 'Você fez login');

        return Route::redirect('GET', '/vpn');
    }

    public function showPasswordReset()
    {
        return View::make('authentication/password_reset_request');
    }

    public function passwordReset(Request $request)
    {
        $loginService = new LoginService();

        if (!$loginService->sendResetPassword($request)) {
            Session::put(Session::FLASH, 'errors', $loginService->messages());

            return Route::redirect('GET', '/password-reset');
        }
    }

    public function resetPassword(Request $request)
    {
        $token = $request->input('token');
        $password = $request->input('password');
        $passwordConfirm = $request->input('password_confirm');

        if ($password !== $passwordConfirm) {
            Session::put(Session::FLASH, 'errors', ['As senhas não coincidem.']);
            return Route::redirect('GET', '/password-reset?token=' . urlencode($token));
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findByResetToken($token);

        if (!$user) {
            Session::put(Session::FLASH, 'errors', ['Token inválido ou expirado.']);
            return Route::redirect('GET', '/password-reset');
        }

        // Verifique validade do token (ex: 1h)
        $createdAt = $user->getResetTokenCreatedAt();
        if (!$createdAt || (new \DateTime())->getTimestamp() - $createdAt->getTimestamp() > 3600) {
            Session::put(Session::FLASH, 'errors', ['Token expirado.']);
            return Route::redirect('GET', '/password-reset');
        }

        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->setResetToken(null);
        $user->setResetTokenCreatedAt(null);
        $userRepository->save($user);

        Session::put(Session::FLASH, 'success', ['Senha redefinida com sucesso!']);
        return Route::redirect('GET', '/login');
    }

    public function sendResetPasswordLink($request)
    {
        $username = $request->input('username');
        if (!$username) {
            $_SESSION['errors'] = ['Informe o usuário.'];
            Session::put(Session::FLASH, 'errors', ['Informe o usuário']);

            return Route::redirect('GET', '/password-reset');
        }

        $service = new LoginService();
        if (!$service->sendResetPassword($request)) {
            Session::put(Session::FLASH, 'errors', $service->messages());
            return Route::redirect('GET', '/password-reset');
        }
        Session::put(Session::FLASH, 'success', ['Se o usuário existir, um e-mail com o link de redefinição foi enviado.']);
        return Route::redirect('GET', '/login');
    }
}
