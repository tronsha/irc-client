<?php

declare(strict_types=1);

namespace App\Service\Irc;

use App\Entity\Irc;
use App\Service\Bot\IdService;
use App\Service\ConsoleService;
use App\Service\PreformService;
use App\Service\SendService;
use Doctrine\ORM\EntityManagerInterface;

class OutputService
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var IdService
     */
    private $botIdService;

    /**
     * @var ConnectionService
     */
    private $connectionService;

    /**
     * @var ConsoleService
     */
    private $consoleService;

    /**
     * @var PreformService
     */
    private $preformService;

    /**
     * @var SendService
     */
    private $sendService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var bool
     */
    private $active = false;

    public function __construct(
        IdService $botIdService,
        ConnectionService $connectionService,
        ConsoleService $consoleService,
        PreformService $preformService,
        SendService $sendService,
        EntityManagerInterface $entityManager
    ) {
        $this->botIdService = $botIdService;
        $this->connectionService = $connectionService;
        $this->consoleService = $consoleService;
        $this->preformService = $preformService;
        $this->sendService = $sendService;
        $this->entityManager = $entityManager;

        $connectionService->setOutputService($this);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return OutputService
     */
    public function setOptions(array $options): OutputService
    {
        $this->options = $options;

        return $this;
    }

    public function enableOutput()
    {
        $this->active = true;
    }

    public function disableOutput()
    {
        $this->active = false;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function preform()
    {
        $this->preformService->preform();
    }

    public function handle()
    {
        if ($this->isActive()) {
            $send = $this->sendService->getSend();
            if (true === is_string($send) && '' !== $send) {
                sleep(1);
                $this->output($send);
            }
        }
    }

    public function output($output)
    {
        $this->connectionService->writeToIrcServer($output);
        $this->entityManager->beginTransaction();
        try {
            $irc = new Irc();
            $irc->setBotId($this->botIdService->getId())
                ->setDirection(2)
                ->setText($output)
                ->setTime(new \DateTime('now'));
            $this->entityManager->persist($irc);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollBack();
        }
        if (true === $this->getOptions()['verbose']) {
            $time = (new \DateTime('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d H:i:s ');
            $this->consoleService->writeToConsole(
                '<timestamp>' . $time . '</timestamp>' .
                '<output>' . $this->consoleService->prepare(
                    $output,
                    true,
                    null,
                    true,
                    true,
                    strlen($time)
                ) . '</output>'
            );
        }
    }
}
