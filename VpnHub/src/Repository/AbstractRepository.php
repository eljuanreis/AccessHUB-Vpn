<?php

namespace App\Repository;

use App\Core\Kernel;

class AbstractRepository
{
    protected function entityManager()
    {
        $application = Kernel::getApplication();
        $orm = $application->get('orm');

        return $orm->getEntityManager();
    }
}
