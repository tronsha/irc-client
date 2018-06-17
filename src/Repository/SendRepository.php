<?php

declare(strict_types = 1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class SendRepository extends EntityRepository
{
    public function getSend(): array
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.priority', 'DESC')
            ->addOrderBy('s.id', 'ASC')
            ->getQuery()
            ->setMaxResults(1);

        return $qb->execute();
    }
}