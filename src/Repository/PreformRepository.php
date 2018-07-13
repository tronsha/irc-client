<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Preform;
use Doctrine\ORM\EntityRepository;

class PreformRepository extends EntityRepository
{
    /**
     * @param string $network
     * @return Preform[]|null
     */
    public function getPreformByNetwork(string $network): ?array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.network = :network')
            ->setParameter('network', $network)
            ->getQuery();

        return $qb->getResult();
    }
}
