<?php

declare(strict_types=1);

namespace App\Service\Irc;

use App\Exception\CouldNotConnectException;
use App\Service\NickService;

class ConnectionService
{
    private $ircServerConnection;

    /**
     * @var OutputService
     */
    private $outputService;

    /**
     * @var NetworkService
     */
    private $networkService;

    /**
     * @var NickService
     */
    private $nickService;

    /**
     * ConnectionService constructor.
     * @param NetworkService $networkService
     */
    public function __construct(NetworkService $networkService, NickService $nickService) {
        $this->networkService = $networkService;
        $this->nickService = $nickService;
    }

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
     * @return resource
     *
     * @throws CouldNotConnectException
     */
    public function connectToIrcServer()
    {
        $errorString = '';
        $errorNumber = 0;

        $ircServerHost = $this->networkService->getIrcServerHost();
        $ircServerPort = $this->networkService->getIrcServerPort();
        $ircServerPassword = $this->networkService->getIrcServerPassword();

        $ircServerIp = gethostbyname($ircServerHost);

        $this->ircServerConnection = fsockopen($ircServerIp, $ircServerPort, $errorNumber, $errorString);

        if (false === $this->ircServerConnection) {
            throw new CouldNotConnectException($errorString);
        }

        if (null !== $ircServerPassword) {
            $this->outputService->output('PASS ' . $ircServerPassword);
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
