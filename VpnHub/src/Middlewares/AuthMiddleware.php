<?php

namespace App\Middlewares;

use App\Utils\Auth;
use App\Utils\Route;

class AuthMiddleware implements MiddlewareInterface
{
    public function execute(): bool
    {
        if (Auth::isAuth()) {
            return true;
        }

        return Route::redirect('GET', '/login');
    }
}
