<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Send;
use App\Service\Bot\IdService;
use Doctrine\ORM\EntityManagerInterface;

class SendService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var IdService
     */
    private $botIdService;

    /**
     * @var string[]
     */
    private $queue = [];

    /**
     * SendService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param IdService              $botIdService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        IdService $botIdService
    ) {
        $this->entityManager = $entityManager;
        $this->botIdService = $botIdService;
    }

    public function getSend()
    {
        if (count($this->queue) > 0) {
            $send = array_shift($this->queue);
        } else {
            $botId = $this->botIdService->getId();
            $sendEntity = $this->entityManager->getRepository(Send::class)->getSend($botId);
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
