<?php

namespace App\Database\Seeders;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\UserService;

class FirstUserSeeder implements SeederInterface
{
    public function execute()
    {
        $user = new User();

        $user->setName('Administrador');
        $user->setEmail('teste@admin.com');
        $user->setUsername('admin');
        $user->setPassword(password_hash('admin', PASSWORD_BCRYPT));
        $user->setIsAdmin(true);
        $user->setLoginAttempts(0);

        $userService = new UserService(new UserRepository());
        $userService->create($user);

        echo "Usuário criado com sucesso!";
    }
}
