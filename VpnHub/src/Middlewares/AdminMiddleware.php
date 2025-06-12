<?php

namespace App\Middlewares;

use App\Utils\Auth;
use App\Utils\Route;

class AdminMiddleware implements MiddlewareInterface
{
    public function execute(): bool
    {
        $auth = Auth::isAuth();

        if (!$auth) {
            return Route::redirect('GET', '/login');
        }

        $userIsAdmin = Auth::getUser()->isAdmin();

        if (!$userIsAdmin) {
            return Route::redirect('GET', '/login');
        }

        return true;
    }
}
