<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Send;
use Doctrine\ORM\EntityRepository;

class SendRepository extends EntityRepository
{
    public function getSend($botId): ?Send
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.botId = :bid')
            ->setParameter('bid', $botId)
            ->orderBy('s.priority', 'DESC')
            ->addOrderBy('s.id', 'ASC')
            ->getQuery()
            ->setMaxResults(1);

        return $qb->getOneOrNullResult();
    }
}
