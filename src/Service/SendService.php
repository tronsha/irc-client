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
     * @var string[]
     */
    private $queue = [];

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
        if (count($this->queue) > 0) {
            $send = array_shift($this->queue);
        } else {
            $sendEntity = $this->entityManager->getRepository(Send::class)->getSend();
            if (null === $sendEntity) {
                return null;
            }
            $send = $sendEntity->getText();
            $this->entityManager->remove($sendEntity);
            $this->entityManager->flush();
        }

        return $send;
    }

    public function setSend($output)
    {
        array_push($this->queue, $output);
    }
}
