<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class IrcEventOn372 extends Event
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
