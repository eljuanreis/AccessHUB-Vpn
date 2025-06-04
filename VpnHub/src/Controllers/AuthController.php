<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Entity\User;
use App\Services\UserService;

class AuthController 
{
    public function show()
    {
        $userService = new UserService();
        echo $userService->create(new User());
        return View::make('authentication/login');
    }
}