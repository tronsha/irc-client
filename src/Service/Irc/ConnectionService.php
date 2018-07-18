<?php

declare(strict_types=1);

namespace App\Service\Irc;

use App\Exception\CouldNotConnectException;

class ConnectionService
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
     * @return ConnectionService
     */
    public function setOutputService(OutputService $outputService): ConnectionService
    {
        $this->outputService = $outputService;

        return $this;
    }

    /**
     * @return OutputService
     */
    public function getOutputService(): OutputService
    {
        return $this->outputService;
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
            $this->getOutputService()->output('PASS ' . $this->ircServerPassword);
        }

        $this->getOutputService()->output('USER Cerberus * * : Cerberus');
        $this->getOutputService()->output('NICK Xoranu');

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