<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Irc\ConnectionService;
use App\Service\Irc\InputService;
use App\Service\Irc\NetworkService;
use App\Service\Irc\OutputService;

class IrcService
{
    /**
     * @var NetworkService
     */
    private $networkService;

    /**
     * @var ConnectionService
     */
    private $connectionService;

    /**
     * @var InputService
     */
    private $inputService;

    /**
     * @var OutputService
     */
    private $outputService;

    /**
     * @var resource
     */
    private $ircServerConnection;

    /**
     * IrcService constructor.
     * @param NetworkService $networkService
     * @param ConnectionService $connectionService
     * @param InputService $inputService
     * @param OutputService $outputService
     */
    public function __construct(
        NetworkService $networkService,
        ConnectionService $connectionService,
        InputService $inputService,
        OutputService $outputService
    ) {
        $this->networkService = $networkService;
        $this->connectionService = $connectionService;
        $this->inputService = $inputService;
        $this->outputService = $outputService;
    }

    /**
     * @throws \App\Exception\CouldNotConnectException
     */
    public function connectToIrcServer()
    {
        $this->ircServerConnection = $this->connectionService->connectToIrcServer();
    }

    /**
     * @throws \Exception
     */
    public function handleIrcInputOutput()
    {
        while (false === feof($this->ircServerConnection)) {
            $inputFromServer = $this->connectionService->readFromIrcServer();
            $this->inputService->handle($inputFromServer);
            $this->outputService->handle();
        }
    }
}
