<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Bot;
use App\Service\Bot\IdService;
use App\Service\Irc\InputService;
use App\Service\Irc\OutputService;
use Doctrine\ORM\EntityManagerInterface;

class BotService
{
    private $pid;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $ircService;
    private $consoleService;
    private $inputService;
    private $outputService;
    private $nickService;
    private $botIdService;

    /**
     * IrcCommand constructor.
     *
     * @param IrcService             $ircService
     * @param ConsoleService         $consoleService
     * @param InputService           $inputService
     * @param OutputService          $outputService
     * @param NickService            $nickService
     * @param IdService              $botIdService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        IrcService $ircService,
        ConsoleService $consoleService,
        InputService $inputService,
        OutputService $outputService,
        NickService $nickService,
        IdService $botIdService,
        EntityManagerInterface $entityManager
    ) {
        $this->ircService = $ircService;
        $this->consoleService = $consoleService;
        $this->inputService = $inputService;
        $this->outputService = $outputService;
        $this->nickService = $nickService;
        $this->botIdService = $botIdService;
        $this->entityManager = $entityManager;
        $inputService->initEvents($this);
    }

    public function setOutputService(OutputService $outputService): BotService
    {
        $this->outputService = $outputService;

        return $this;
    }

    public function getOutputService(): OutputService
    {
        return $this->outputService;
    }

    public function setNickService(NickService $nickService): BotService
    {
        $this->nickService = $nickService;

        return $this;
    }

    public function getNickService(): NickService
    {
        return $this->nickService;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->botIdService->getId();
    }

    /**
     * @return int
     */
    public function getPid()
    {
        if (null === $this->pid) {
            $this->pid = getmypid();
        }

        return $this->pid;
    }

    /**
     * @return string
     */
    public function getNick()
    {
        return $this->nickService->getNick();
    }

    public function create()
    {
        $this->entityManager->beginTransaction();
        try {
            $bot = new Bot();
            $bot->setPid($this->getPid());
            $bot->setNick($this->nickService->getNick());
            $this->entityManager->persist($bot);
            $this->entityManager->flush();
            $this->entityManager->commit();
            $botId = $bot->getId();
            $this->botIdService->setId($botId);

            return $botId;
        } catch (\Exception $exception) {
            $this->entityManager->rollBack();

            return null;
        }
    }

    /**
     * @throws \App\Exception\CouldNotConnectException
     * @throws \Exception
     */
    public function run()
    {
        $this->outputService->preform();
        $this->ircService->connectToIrcServer();
        $this->ircService->handleIrcInputOutput();
    }
}
