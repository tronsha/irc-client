<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\CouldNotConnectException;
use App\Service\Irc\OutputService;

class IrcService
{
    private $ircServerName;
    private $ircServerPort;
    private $ircServerPassword;
    private $ircServerConnection;

    /**
     * @var OutputService
     */
    private $outputService;

    /**
     * @param OutputService $outputService
     * @return IrcService
     */
    public function setOutputService(OutputService $outputService): IrcService
    {
        $this->outputService = $outputService;

        return $this;
    }


    /**
     * @param string      $server
     * @param int         $port
     * @param string|null $password
     */
    public function setIrcServer(string $server, int $port, string $password = null)
    {
        $this->ircServerName = $server;
        $this->ircServerPort = $port;
        $this->ircServerPassword = $password;
    }

    /**
     * @return resource
     *
     * @throws CouldNotConnectException
     */
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
            $this->outputService->output('PASS ' . $this->ircServerPassword);
        }

        $this->outputService->output('USER Cerberus * * : Cerberus');
        $this->outputService->output('NICK Xoranu');

        return $this->ircServerConnection;
    }

    /**
     * @return string
     */
    public function readFromIrcServer(): string
    {
        return trim((string) fgets($this->ircServerConnection, 4096));
    }

    /**
     * @param string $text
     */
    public function writeToIrcServer(string $text)
    {
        fwrite($this->ircServerConnection, $text . PHP_EOL);
    }
}
