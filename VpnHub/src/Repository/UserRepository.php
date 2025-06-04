<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository
{
    public function create(User $user): bool
    {
        try {
            $user = new User();

            $user->setUsername('johndoe');
            $user->setPassword(password_hash('senhaSegura123', PASSWORD_BCRYPT));
            $user->setLoginAttempts(0);

            $this->entityManager()->persist($user);
            $this->entityManager()->flush();

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findByUsername(string $username): ?User
    {
        return $this->entityManager()
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
    }
}