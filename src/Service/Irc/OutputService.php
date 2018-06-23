<?php

declare(strict_types=1);

namespace App\Service\Irc;

use App\Service\ConsoleService;
use App\Service\IrcService;
use App\Service\PreformService;
use App\Service\SendService;

class OutputService
{
    /**
     * @var IrcService
     */
    private $ircService;

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

    public function __construct(PreformService $preformService, SendService $sendService)
    {
        $this->setPreformService($preformService);
        $this->setSendService($sendService);
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
     * @return IrcService
     */
    public function getIrcService(): IrcService
    {
        return $this->ircService;
    }

    /**
     * @param IrcService $ircService
     * @return OutputService
     */
    public function setIrcService(IrcService $ircService): OutputService
    {
        $this->ircService = $ircService;

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

    public function preform()
    {

    }

    public function handle()
    {

    }

}
