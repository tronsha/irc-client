<?php

declare(strict_types = 1);

namespace App\Service;

class NickService
{
    private $nick;

    public function setNick($nick)
    {
        $this->nick = $nick;
    }

    public function getNick()
    {
        if (null === $this->nick) {
            return $this->getRandomNick();
        }
        return $this->nick;
    }

    private function generateRandomNick()
    {
        $consonant = str_split('bcdfghjklmnpqrstvwxyz');
        $vowel = str_split('aeiou');
        $nick = '';
        for ($i = 0; $i < 3; $i++) {
            $nick .= $consonant[mt_rand(0, 20)] . $vowel[mt_rand(0, 4)];
        }

        return ucfirst($nick);
    }

    public function getRandomNick()
    {
        $randomNick = $this->generateRandomNick();
        $this->setNick($randomNick);

        return $randomNick;
    }
}
