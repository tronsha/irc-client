<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Irc\InputService;
use App\Service\Irc\OutputService;

class BotService
{
    private $pid;

    private $ircService;
    private $consoleService;
    private $inputService;
    private $outputService;
    private $nickService;

    /**
     * IrcCommand constructor.
     * @param IrcService $ircService
     * @param ConsoleService $consoleService
     * @param InputService $inputService
     * @param OutputService $outputService
     * @param NickService $nickService
     */
    public function __construct(
        IrcService $ircService,
        ConsoleService $consoleService,
        InputService $inputService,
        OutputService $outputService,
        NickService $nickService
    ) {
        $this->ircService = $ircService;
        $this->consoleService = $consoleService;
        $this->inputService = $inputService;
        $this->outputService = $outputService;
        $this->nickService = $nickService;
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
        // TODO create bot
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
