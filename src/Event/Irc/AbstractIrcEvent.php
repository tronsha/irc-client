<?php

namespace App\Event\Irc;

use Symfony\Component\EventDispatcher\Event;

abstract class AbstractIrcEvent extends Event
{
    private $data;

    public function __construct($data)
    {
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
