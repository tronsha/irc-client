<?php

declare(strict_types = 1);

namespace App\Command;

use App\Service\BotService;
use App\Service\ConsoleService;
use App\Service\Irc\ConnectionService;
use App\Service\Irc\InputService;
use App\Service\Irc\NetworkService;
use App\Service\Irc\OutputService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IrcCommand extends ContainerAwareCommand
{
    /**
     * @var BotService
     */
    private $botService;

    /**
     * @var ConsoleService
     */
    private $consoleService;

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
     * @var NetworkService
     */
    private $networkService;

    /**
     * @var resource
     */
    private $ircServerConnection;

    /**
     * @return resource
     */
    public function getIrcServerConnection()
    {
        return $this->ircServerConnection;
    }

    /**
     * @param resource $ircServerConnection
     * @return IrcCommand
     */
    public function setIrcServerConnection($ircServerConnection): IrcCommand
    {
        $this->ircServerConnection = $ircServerConnection;

        return $this;
    }

    public function __construct(
        BotService $botService,
        NetworkService $networkService,
        ConnectionService $connectionService,
        ConsoleService $consoleService,
        InputService $inputService,
        OutputService $outputService
    ) {
        parent::__construct();
        $this->botService = $botService;
        $this->networkService = $networkService;
        $this->connectionService = $connectionService;
        $this->consoleService = $consoleService;
        $this->inputService  = $inputService;
        $this->outputService = $outputService;
    }

    protected function configure()
    {
        $this->setName('irc:run');
        $this->addArgument('host',  InputArgument::OPTIONAL, 'Server Host?');
        $this->addArgument('port', InputArgument::OPTIONAL, 'Server Port?');
        $this->addArgument('password', InputArgument::OPTIONAL, 'Server Password?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->networkService->setArguments($input->getArguments());
            $this->consoleService->setOutput($output);
            $this->inputService->setOptions($input->getOptions());
            $this->outputService->setOptions($input->getOptions());
            $this->outputService->preform();
            $this->connectToIrcServer();
            $this->handleIrcInputOutput();
        } catch (Exception $exception) {
            $this->consoleService->writeToConsole('<error>' . $exception->getMessage() . '</error>');
        }
    }

    protected function connectToIrcServer()
    {
        $this->setIrcServerConnection($this->connectionService->connectToIrcServer());
    }

    protected function handleIrcInputOutput()
    {
        while (false === feof($this->getIrcServerConnection())) {
            $inputFromServer = $this->connectionService->readFromIrcServer();
            $this->inputService->handle($inputFromServer);
            $this->outputService->handle();
        }
    }
}
