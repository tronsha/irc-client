<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Preform;
use App\Entity\Send;
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
     * PreformService constructor.
     * @param EntityManagerInterface $entityManager
     * @param NetworkService $networkService
     */
    public function __construct(EntityManagerInterface $entityManager, NetworkService $networkService)
    {
        $this->entityManager = $entityManager;
        $this->networkService = $networkService;
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
                $send->setBotId(1);
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
