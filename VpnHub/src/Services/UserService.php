<?php

namespace App\Services;

use App\Core\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Validators\Entities\UserValidator;

class UserService
{
    public function create(User $user)
    {
        $validator = new UserValidator();

        if (!$validator->create($user)) {
            throw new \Exception(implode(',', $validator->messages()));
        }

        try {
            $repository = new UserRepository();
            $repository->save($user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
