<?php

namespace App\Event\Irc;

class On432 extends AbstractIrcEvent
{
    public function on432()
    {
        $randomNick = $this->getBotService()->getNickService()->getRandomNick();
        $this->getBotService()->getOutputService()->output('NICK ' . $randomNick);
    }
}
