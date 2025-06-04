<?php

namespace App\Database\Seeders;

use App\Entity\User;
use App\Services\UserService;

class FirstUserSeeder implements SeederInterface
{
    public function execute()
    {
        $user = new User();

        $user->setUsername('admin10');
        $user->setPassword(password_hash('admin', PASSWORD_BCRYPT));
        $user->setLoginAttempts(0);

        $userService = new UserService();
        $userService->create($user);

        echo "Usuário criado com sucesso!";
    }
}
