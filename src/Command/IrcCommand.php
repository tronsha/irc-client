<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Irc\InputHandler;
use App\Service\IrcService;
use App\Service\ConsoleService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IrcCommand extends Command
{
    /**
     * @var ConsoleService
     */
    private $consoleService;

    /**
     * @var InputHandler
     */
    private $inputHandler;

    /**
     * @var IrcService
     */
    private $ircService;

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
     * @return InputHandler
     */
    public function getInputHandler(): InputHandler
    {
        return $this->inputHandler;
    }

    /**
     * @param InputHandler $inputHandler
     * @return IrcCommand
     */
    public function setInputHandler(InputHandler $inputHandler): IrcCommand
    {
        $this->inputHandler = $inputHandler;

        return $this;
    }

    /**
     * @return IrcService
     */
    public function getIrcService(): IrcService
    {
        return $this->ircService;
    }

    /**
     * @param IrcService $ircService
     * @return IrcCommand
     */
    public function setIrcService(IrcService $ircService): IrcCommand
    {
        $this->ircService = $ircService;

        return $this;
    }

    public function __construct(IrcService $ircService, ConsoleService $consoleService, InputHandler $inputHandler)
    {
        $this->setConsoleService($consoleService);
        $this->setInputHandler($inputHandler);
        $this->setIrcService($ircService);
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('irc:run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->getConsoleService()->setOutput($output);
            $this->getInputHandler()
                ->setConsoleService($this->getConsoleService())
                ->setIrcService($this->getIrcService())
                ->setOptions($input->getOptions());
            $ircServerConnection = $this->getIrcService()->connectToIrcServer();
            while (false === feof($ircServerConnection)) {
                $inputFromServer = $this->getIrcService()->readFromIrcServer();
                $this->getInputHandler()->handle($inputFromServer);
            }
        } catch (Exception $exception) {
            $this->getConsoleService()->writeToConsole('<error>' . $exception->getMessage() . '</error>');
        }
    }
}
