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
        $this->setConnectionService($connectionService);
        $this->setConsoleService($consoleService);
        $this->setPreformService($preformService);
        $this->setSendService($sendService);

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

    /**
     * @return PreformService
     */
    public function getPreformService(): PreformService
    {
        return $this->preformService;
    }

    /**
     * @param PreformService $preformService
     * @return OutputService
     */
    public function setPreformService(PreformService $preformService): OutputService
    {
        $this->preformService = $preformService;

        return $this;
    }

    /**
     * @return SendService
     */
    public function getSendService(): SendService
    {
        return $this->sendService;
    }

    /**
     * @param SendService $sendService
     * @return OutputService
     */
    public function setSendService(SendService $sendService): OutputService
    {
        $this->sendService = $sendService;

        return $this;
    }

    /**
     * @return ConnectionService
     */
    public function getConnectionService(): ConnectionService
    {
        return $this->connectionService;
    }

    /**
     * @param ConnectionService $connectionService
     * @return OutputService
     */
    public function setConnectionService(ConnectionService $connectionService): OutputService
    {
        $this->connectionService = $connectionService;

        return $this;
    }

    /**
     * @return ConsoleService
     */
    public function getConsoleService(): ConsoleService
    {
        return $this->consoleService;
    }

    /**
     * @param ConsoleService $consoleService
     * @return OutputService
     */
    public function setConsoleService(ConsoleService $consoleService): OutputService
    {
        $this->consoleService = $consoleService;

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
        $this->getPreformService()->preform();
    }

    public function handle()
    {
        if ($this->isActive()) {
            $send = $this->getSendService()->getSend();
            if (is_string($send) && !empty($send)) {
                sleep(1);
                $this->output($send);
            }
        }
    }

    public function output($output)
    {
        $this->getConnectionService()->writeToIrcServer($output);
        if (true === $this->getOptions()['verbose']) {
            $this->getConsoleService()->writeToConsole($output);
        }
    }
}
