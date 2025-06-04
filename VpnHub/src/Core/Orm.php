<?php

namespace App\Core;

use App\Utils\Env;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

class Orm
{
    protected EntityManager $entityManager;

    public function load($entityPath)
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [$entityPath],
            isDevMode: Env::get('DEBUG') ?? false
        );

        $connection = DriverManager::getConnection([
                'driver' => Env::get('DB_DRIVER'),
                'host' => Env::get('DB_HOST'),
                'port' => Env::get('DB_PORT'),
                'dbname' => Env::get('DB_DATABASE'),
                'user' => Env::get('DB_USERNAME'),
                'password' => Env::get('DB_PASSWORD'),
                'charset' => Env::get('DB_CHARSET'),
        ], $config);

        $this->entityManager = new EntityManager($connection, $config);
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
