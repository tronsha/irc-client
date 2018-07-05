<?php

namespace App\Event\Irc;

use App\Service\Irc\OutputService;
use App\Service\IrcService;
use App\Service\NickService;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractIrcEvent extends Event
{
    protected $data;

    private $ircService;

    private $outputService;

    private $nickService;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param mixed $ircService
     * @return AbstractIrcEvent
     */
    public function setIrcService($ircService): Event
    {
        $this->ircService = $ircService;

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
     * @param OutputService $outputService
     * @return Event
     */
    public function setOutputService(OutputService $outputService): Event
    {
        $this->outputService = $outputService;

        return $this;
    }

    /**
     * @return OutputService
     */
    public function getOutputService(): OutputService
    {
        return $this->outputService;
    }

    /**
     * @param mixed $nickService
     * @return Event
     */
    public function setNickService($nickService): Event
    {
        $this->nickService = $nickService;

        return $this;
    }

    /**
     * @return NickService
     */
    public function getNickService(): NickService
    {
        return $this->nickService;
    }

    public function handle()
    {
        $method = lcfirst(str_replace('App\\Event\\Irc\\', '', get_class($this)));
        if (true === method_exists($this, $method)) {
            $this->$method();
        }
    }
}
