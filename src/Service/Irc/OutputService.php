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
     * @return OutputHandler
     */
    public function setPreformService(PreformService $preformService): OutputHandler
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
     * @return OutputHandler
     */
    public function setSendService(SendService $sendService): OutputHandler
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
     * @return OutputHandler
     */
    public function setIrcService(IrcService $ircService): OutputHandler
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
     * @return OutputHandler
     */
    public function setConsoleService(ConsoleService $consoleService): OutputHandler
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
