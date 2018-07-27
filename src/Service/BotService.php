<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Irc\OutputService;

class BotService
{
    private $pid;

    private $outputService;
    private $nickService;

    public function __construct(
        OutputService $outputService,
        NickService $nickService
    ) {
        $this->outputService = $outputService;
        $this->nickService = $nickService;
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

    public function getPid()
    {
        if (null === $this->pid) {
            $this->pid = getmypid();
        }
        return $this->pid;
    }

}
