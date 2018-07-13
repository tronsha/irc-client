<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Preform;
use App\Entity\Send;
use Doctrine\ORM\EntityManagerInterface;

class PreformService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * PreformService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return bool
     */
    public function preform(): bool
    {
        $this->entityManager->beginTransaction();
        try {
            $preforms = $this->entityManager->getRepository(Preform::class);
            foreach ($preforms->getPreformByNetwork('freenode') as $preform) {
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
