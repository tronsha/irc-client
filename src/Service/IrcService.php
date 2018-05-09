<?php

declare (strict_types=1);

namespace App\Service;

class IrcService
{
    public function run($output)
    {
        $output->writeln([
                __CLASS__,
            ]);
    }
}
