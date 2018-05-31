<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\CouldNotConnectException;
use Symfony\Component\EventDispatcher\EventDispatcher;

class IrcService
{
    private $ircServerName;
    private $ircServerPort;
    private $ircServerPassword;
    private $ircServerConnection;

    public function setIrcServer(string $server, int $port, string $password = null)
    {
        $this->ircServerName = $server;
        $this->ircServerPort = $port;
        $this->ircServerPassword = $password;
    }

    public function connectToServer()
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
        
        return $this->ircServerConnection;
    }

    public function readFromServer(): string
    {
        return fgets($this->ircServerConnection, 4096);
    }

    public function writeToServer($text)
    {
        fwrite($this->ircServerConnection, $text . PHP_EOL);
    }
}
