<?php

namespace App\Event\Irc;

use App\Service\Irc\OutputService;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractIrcEvent extends Event
{
    protected $data;
    protected $ircOutputService;

    public function __construct($data, OutputService $ircOutputService)
    {
        $this->ircOutputService = $ircOutputService;
        $this->data = $data;
    }

    public function handle()
    {
        $method = lcfirst(str_replace('App\\Event\\Irc\\', '', get_class($this)));
        if (true === method_exists($this, $method)) {
            $this->$method();
        }
    }
}
