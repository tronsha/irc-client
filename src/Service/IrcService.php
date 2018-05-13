<?php

declare (strict_types = 1);

namespace App\Service;

class IrcService
{
    private $ircServerName;
    private $ircServerPort;
    private $ircServerConnection;

    public function __construct($server, $port)
    {
        $this->ircServerName = $server;
        $this->ircServerPort = $port;
    }

    public function run($outputStream)
    {
        try {
            $this->connect();
            while (!feof($this->ircServerConnection)) {
                $input = fgets($this->ircServerConnection, 4096);
                if (is_string($input)) {
                    if (':' !== substr($input, 0, 1)) {
                        if (false !== strpos(strtoupper($input), 'PING')) {
                            $output = str_replace('PING', 'PONG', $input);
                            fwrite($this->ircServerConnection, $output . PHP_EOL);
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

    private function connect()
    {
        $ircServerIp = gethostbyname($this->ircServerName);
        $this->ircServerConnection = fsockopen($ircServerIp, $this->ircServerPort, $errorNumber, $errorString);
        fwrite($this->ircServerConnection, 'USER Cerberus * * : Cerberus' . PHP_EOL);
        fwrite($this->ircServerConnection, 'NICK Cerber' . PHP_EOL);
    }
}
