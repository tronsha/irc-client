<?php

declare(strict_types=1);

namespace App\Service;

class NickService
{
    public static function getRandomNick()
    {
        $consonant = str_split('bcdfghjklmnpqrstvwxyz');
        $vowel = str_split('aeiou');
        $nick = '';
        for ($i = 0; $i < 3; $i++) {
            $nick .= $consonant[mt_rand(0, 20)] . $vowel[mt_rand(0, 4)];
        }

        return ucfirst($nick);
    }
}
