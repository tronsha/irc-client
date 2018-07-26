<?php

namespace App\Event\Irc;

class On433 extends AbstractIrcEvent
{
    public function on433()
    {
        $randomNick = $this->getBotService()->getNickService()->getRandomNick();
        $this->getBotService()->getOutputService()->output('NICK ' . $randomNick);
    }
}
