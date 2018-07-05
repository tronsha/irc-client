<?php

namespace App\Event\Irc;

class On432 extends AbstractIrcEvent
{
    public function on432()
    {
        $randomNick = $this->getNickService()->getRandomNick();
        $this->getIrcService()->writeToIrcServer('NICK ' . $randomNick);
    }
}
