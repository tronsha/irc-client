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

    public function run($outputStream)
    {
        try {
            $ip = gethostbyname($this->server);
            $fp = fsockopen($ip, $this->port, $errno, $errstr);
            fwrite($fp, 'USER Cerberus * * : Cerberus' . PHP_EOL);
            fwrite($fp, 'NICK Cerber' . PHP_EOL);
            while (!feof($fp)) {
                $input = fgets($fp, 4096);
                if (is_string($input)) {
                    if (':' !== substr($input, 0, 1)) {
                        if (false !== strpos(strtoupper($input), 'PING')) {
                            $output = str_replace('PING', 'PONG', $input);
                            fwrite($fp, $output . PHP_EOL);
                        }
                    }
                    $outputStream->writeln([
                        trim($input),
                    ]);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
