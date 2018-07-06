<?php

namespace App\Event\Irc;

class On433 extends AbstractIrcEvent
{
    public function on433()
    {
        $randomNick = $this->getNickService()->getRandomNick();
        $this->getOutputService()->output('NICK ' . $randomNick);
    }
}
