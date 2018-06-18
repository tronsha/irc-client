<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Send;
use Doctrine\ORM\EntityRepository;

class SendRepository extends EntityRepository
{
    public function getSend(): Send
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.priority', 'DESC')
            ->addOrderBy('s.id', 'ASC')
            ->getQuery()
            ->setMaxResults(1);

        return $qb->getSingleResult();
    }
}