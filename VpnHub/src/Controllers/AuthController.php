<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Services\LoginService;
use App\Utils\Route;
use App\Utils\Session;
use App\Utils\SessionFlash;

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

        return Route::redirect('GET', '/painel');
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
}
