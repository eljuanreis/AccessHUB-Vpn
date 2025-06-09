<?php

namespace App\Repository;

use App\Entity\Configuration;

class ConfigurationRepository extends AbstractRepository
{
    public function save(Configuration $config): bool
    {
        try {
            $this->entityManager()->persist($config);
            $this->entityManager()->flush();

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findByIdentifier(string $id)
    {
        return $this->entityManager()
            ->getRepository(Configuration::class)
            ->findOneBy(['identifier' => $id]);
    }

    public function remove(Configuration $config): bool
    {
        try {
            $this->entityManager()->remove($config);
            $this->entityManager()->flush();

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
