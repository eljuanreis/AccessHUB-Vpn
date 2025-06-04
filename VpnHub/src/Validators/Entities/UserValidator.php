<?php

namespace App\Validators\Entities;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Validators\EntityValidatorInterface;

class UserValidator implements EntityValidatorInterface
{
    protected array $messageBag = [];

    public function create($object): bool
    {
        if (empty($object->getUsername())) {
            $this->messageBag[] = 'Informe o username';
        }

        if (empty($object->getPassword())) {
            $this->messageBag[] = 'Informe o password do usuário';
        }

        $repository = new UserRepository();
        if ($repository->findByUsername($object->getUsername())) {
            $this->messageBag[] = 'Já existe um usuário com esse nome';
        }

        if (count($this->messageBag) > 0) {
            return false;
        }

        return true;
    }

    public function messages(): array
    {
        return $this->messageBag;
    }
}
