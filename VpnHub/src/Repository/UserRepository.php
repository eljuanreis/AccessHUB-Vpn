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

    public function remove(User $user): bool
    {
        try {
            $this->entityManager()->remove($user);
            $this->entityManager()->flush();

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findById(int $id): ?User
    {
        return $this->entityManager()
            ->getRepository(User::class)
            ->find($id);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->entityManager()
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->entityManager()
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }
}
