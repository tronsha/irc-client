<?php

namespace App\Event\Irc;

use App\Service\IrcService;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractIrcEvent extends Event
{
    private $ircService;
    private $data;

    public function __construct(IrcService $ircService, $data)
    {
        $this->ircService = $ircService;
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
