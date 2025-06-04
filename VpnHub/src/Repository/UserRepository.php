<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository
{
    public function save(User $user): bool
    {
        try {
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
