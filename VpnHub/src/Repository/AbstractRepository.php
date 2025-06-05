<?php

namespace App\Repository;

use App\Core\Kernel;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AbstractRepository
{
    protected function entityManager()
    {
        $application = Kernel::getApplication();
        $orm = $application->get('orm');

        return $orm->getEntityManager();
    }

    public function webPaginate(WebQueryBuilder $webQueryBuilder): array
    {
        $qb = $this->entityManager()
            ->createQueryBuilder()
            ->select('q')
            ->from($webQueryBuilder->getTableName(), 'q')
            ->orderBy('q.' . $webQueryBuilder->getOrderBy()['field'], $webQueryBuilder->getOrderBy()['type'])
            ->setFirstResult(($webQueryBuilder->getPage() - 1) * $webQueryBuilder->getLimit())
            ->setMaxResults($webQueryBuilder->getLimit());
        
        foreach ($webQueryBuilder->getWheres() as $where) {
            list($field, $operator, $value) = $where;

            $qb->where(sprintf('q.%s %s :%s', $field, $operator, $field))
                ->setParameter($field, $value);
        }

        $paginator = new Paginator($qb);

        return [
            'data' => iterator_to_array($paginator),
            'total' => count($paginator),
            'page' => $webQueryBuilder->getPage(),
            'limit' =>  $webQueryBuilder->getLimit(),
            'pages' => ceil(count($paginator) /  $webQueryBuilder->getLimit())
        ];
    }
}
