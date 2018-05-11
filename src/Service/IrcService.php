<?php

declare (strict_types = 1);

namespace App\Service;

class IrcService
{
    private $server;
    private $port;

    public function __construct($server, $port)
    {
        $this->server = $server;
        $this->port = $port;
    }

    public function run($output)
    {
        $output->writeln([
            __CLASS__,
            $this->server,
            $this->port,
        ]);
    }
}
