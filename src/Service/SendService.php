<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Send;
use Doctrine\ORM\EntityManagerInterface;

class SendService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SendService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getSend()
    {
        $sendEntity = $this->entityManager->getRepository(Send::class)->getSend();

        if (null === $sendEntity) {
            return null;
        }

        $send = $sendEntity->getText();
        $this->entityManager->remove($sendEntity);
        $this->entityManager->flush();

        return $send;
    }

}
