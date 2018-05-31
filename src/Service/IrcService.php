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

    public function setIrcServer(string $server, int $port, string $password = null)
    {
        $this->ircServerName = $server;
        $this->ircServerPort = $port;
        $this->ircServerPassword = $password;
    }

    public function connectToIrcServer()
    {
        $errorString = '';
        $errorNumber = 0;

        $ircServerIp = gethostbyname($this->ircServerName);

        $this->ircServerConnection = fsockopen($ircServerIp, $this->ircServerPort, $errorNumber, $errorString);

        if (false === $this->ircServerConnection) {
            throw new CouldNotConnectException($errorString);
        }

        if (null !== $this->ircServerPassword) {
            $this->writeToIrcServer('PASS ' . $this->ircServerPassword);
        }

        $this->writeToIrcServer('USER Cerberus * * : Cerberus');
        $this->writeToIrcServer('NICK Cerber');

        return $this->ircServerConnection;
    }

    public function readFromIrcServer(): string
    {
        return trim((string) fgets($this->ircServerConnection, 4096));
    }

    public function writeToIrcServer($text)
    {
        fwrite($this->ircServerConnection, $text . PHP_EOL);
    }
}
