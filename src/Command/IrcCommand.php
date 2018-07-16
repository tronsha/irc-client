<?php

declare(strict_types = 1);

namespace App\Command;

use App\Service\ConsoleService;
use App\Service\Irc\ConnectionService;
use App\Service\Irc\InputService;
use App\Service\Irc\OutputService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IrcCommand extends ContainerAwareCommand
{
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
     * @var resource
     */
    private $ircServerConnection;

    /**
     * @return ConsoleService
     */
    public function getConsoleService(): ConsoleService
    {
        return $this->consoleService;
    }

    /**
     * @param ConsoleService $consoleService
     * @return IrcCommand
     */
    public function setConsoleService(ConsoleService $consoleService): IrcCommand
    {
        $this->consoleService = $consoleService;

        return $this;
    }

    /**
     * @return InputService
     */
    public function getInputService(): InputService
    {
        return $this->inputService;
    }

    /**
     * @param InputService $inputService
     * @return IrcCommand
     */
    public function setInputService(InputService $inputService): IrcCommand
    {
        $this->inputService = $inputService;

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
     * @param OutputService $outputService
     * @return IrcCommand
     */
    public function setOutputService(OutputService $outputService): IrcCommand
    {
        $this->outputService = $outputService;

        return $this;
    }

    /**
     * @return ConnectionService
     */
    public function getConnectionService(): ConnectionService
    {
        return $this->connectionService;
    }

    /**
     * @param ConnectionService $connectionService
     * @return IrcCommand
     */
    public function setConnectionService(ConnectionService $connectionService): IrcCommand
    {
        $this->connectionService = $connectionService;

        return $this;
    }

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

    /**
     * @return PreformService
     */
    public function getPreformService(): PreformService
    {
        return $this->preformService;
    }

    public function __construct(
        ConnectionService $connectionService,
        ConsoleService $consoleService,
        InputService $inputService,
        OutputService $outputService
    ) {
        parent::__construct();
        $this->setConsoleService($consoleService);
        $this->setConnectionService($connectionService);
        $this->setInputService($inputService);
        $this->setOutputService($outputService);
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
            if (isset($input->getArguments()['host']) && isset($input->getArguments()['port'])) {
                $this->getConnectionService()->setIrcServer(
                    $input->getArguments()['host'],
                    (int) $input->getArguments()['port'],
                    $input->getArguments()['password']
                );
            }

            $this->getConsoleService()->setOutput($output);
            $this->getInputService()->setOptions($input->getOptions());
            $this->getOutputService()->setOptions($input->getOptions());
            $this->getOutputService()->preform();
            $this->connectToIrcServer();
            $this->handleIrcInputAndOutput();
        } catch (Exception $exception) {
            $this->getConsoleService()->writeToConsole('<error>' . $exception->getMessage() . '</error>');
        }
    }

    protected function connectToIrcServer()
    {
        $this->setIrcServerConnection($this->getConnectionService()->connectToIrcServer());
    }

    protected function handleIrcInputAndOutput()
    {
        while (false === feof($this->getIrcServerConnection())) {
            $inputFromServer = $this->getConnectionService()->readFromIrcServer();
            $this->getInputService()->handle($inputFromServer);
            $this->getOutputService()->handle();
        }
    }
}
