<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\CouldNotConnectException;

class IrcService
{
    private $ircServerName;
    private $ircServerPort;
    private $ircServerPassword;
    private $ircServerConnection;
    private $outputService;

    public function __construct(OutputService $outputService)
    {
        $this->outputService = $outputService;
    }

    public function setIrcServer(string $server, int $port, string $password = null)
    {
        $this->ircServerName = $server;
        $this->ircServerPort = $port;
        $this->ircServerPassword = $password;
    }

    public function run()
    {
        try {
            $this->connectToServer();
            while (false === feof($this->ircServerConnection)) {
                $this->readFromServer();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function connectToServer()
    {
        $errorString = '';
        $errorNumber = 0;

        $ircServerIp = gethostbyname($this->ircServerName);

        $this->ircServerConnection = fsockopen($ircServerIp, $this->ircServerPort, $errorNumber, $errorString);

        if (false === $this->ircServerConnection) {
            throw new CouldNotConnectException($errorString);
        }

        if (null !== $this->ircServerPassword) {
            $this->writeToServer('PASS ' . $this->ircServerPassword);
        }

        $this->writeToServer('USER Cerberus * * : Cerberus');
        $this->writeToServer('NICK Cerber');
    }

    private function readFromServer()
    {
        $input = fgets($this->ircServerConnection, 4096);
        if (true === is_string($input) && '' !== $input) {
            $input = trim($input);
            if (':' !== substr($input, 0, 1)) {
                if (false !== strpos(strtoupper($input), 'PING')) {
                    $output = str_replace('PING', 'PONG', $input);
                    $this->writeToServer($output);
                }
            }
            $this->outputService->write($input);
        }
    }

    private function writeToServer($text)
    {
        fwrite($this->ircServerConnection, $text . PHP_EOL);
    }
}
