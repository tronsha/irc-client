<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Preform;
use App\Entity\Send;
use App\Service\Bot\IdService;
use App\Service\Irc\NetworkService;
use Doctrine\ORM\EntityManagerInterface;

class PreformService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var NetworkService
     */
    private $networkService;

    /**
     * @var IdService
     */
    private $botIdService;

    /**
     * PreformService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param NetworkService         $networkService
     * @param IdService              $botIdService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        NetworkService $networkService,
        IdService $botIdService
    ) {
        $this->entityManager = $entityManager;
        $this->networkService = $networkService;
        $this->botIdService = $botIdService;
    }

    /**
     * @return bool
     */
    public function preform(): bool
    {
        $this->entityManager->beginTransaction();
        try {
            $preforms = $this->entityManager->getRepository(Preform::class);
            foreach ($preforms->getPreformByNetwork($this->networkService->getNetwork()) as $preform) {
                $send = new Send();
                $send->setBotId($this->botIdService->getId());
                $send->setText($preform->getText());
                $send->setPriority($preform->getPriority());
                $this->entityManager->persist($send);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();

            return true;
        } catch (\Exception $exception) {
            $this->entityManager->rollBack();

            return false;
        }
    }
}
