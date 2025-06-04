<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Services\LoginService;
use App\Utils\Route;
use App\Utils\Session;
use App\Utils\SessionFlash;
use App\Validators\Web\LoginValidator;

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

        return 'foi';
    }
}
