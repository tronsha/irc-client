<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;

class IrcEvent464 extends Event
{
    private $input;


    public function __construct($input) 
    {
        $this->input = $input;
    }

    public function run()
    {
        var_dump($this->input);
        die(PHP_EOL);
    }
}