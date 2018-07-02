<?php

namespace App\Event\Irc;

use App\Service\Irc\OutputService;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractIrcEvent extends Event
{
    protected $data;

    private $outputService;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function setOutputService(OutputService $outputService): Event
    {
        $this->outputService = $outputService;

        return $this;
    }

    public function getOutputService(): OutputService
    {
        return $this->outputService;
    }

    public function handle()
    {
        $method = lcfirst(str_replace('App\\Event\\Irc\\', '', get_class($this)));
        if (true === method_exists($this, $method)) {
            $this->$method();
        }
    }
}
