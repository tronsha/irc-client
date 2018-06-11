<?php

namespace App\Event\Irc;

use Symfony\Component\EventDispatcher\Event;

class On372 extends Event
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
    }
}
