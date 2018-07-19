<?php

declare(strict_types=1);

namespace App\Service\Irc;

use App\Service\ConsoleService;
use App\Service\PreformService;
use App\Service\SendService;

class OutputService
{
    /**
     * @var array
     */
    private $options = [];

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
     * @var bool
     */
    private $active = false;

    public function __construct(
        ConnectionService $connectionService,
        ConsoleService $consoleService,
        PreformService $preformService,
        SendService $sendService
    ) {
        $this->connectionService = $connectionService;
        $this->consoleService = $consoleService;
        $this->preformService = $preformService;
        $this->sendService = $sendService;

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
            if (is_string($send) && !empty($send)) {
                sleep(1);
                $this->output($send);
            }
        }
    }

    public function output($output)
    {
        $this->connectionService->writeToIrcServer($output);
        if (true === $this->getOptions()['verbose']) {
            $this->consoleService->writeToConsole($output);
        }
    }
}
