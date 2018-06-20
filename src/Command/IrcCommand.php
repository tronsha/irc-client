<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ConsoleService;
use App\Service\IrcService;
use App\Service\Irc\InputHandler;
use App\Service\Irc\OutputHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IrcCommand extends ContainerAwareCommand
{
    /**
     * @var ConsoleService
     */
    private $consoleService;

    /**
     * @var IrcService
     */
    private $ircService;

    /**
     * @var InputHandler
     */
    private $inputHandler;

    /**
     * @var OutputHandler
     */
    private $outputHandler;

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
     * @return OutputHandler
     */
    public function getOutputHandler(): OutputHandler
    {
        return $this->outputHandler;
    }

    /**
     * @param OutputHandler $outputHandler
     * @return IrcCommand
     */
    public function setOutputHandler(OutputHandler $outputHandler): IrcCommand
    {
        $this->outputHandler = $outputHandler;

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

    /**
     * @return PreformService
     */
    public function getPreformService(): PreformService
    {
        return $this->preformService;
    }



    public function __construct(
        IrcService $ircService,
        ConsoleService $consoleService,
        InputHandler $inputHandler,
        OutputHandler $outputHandler
    ) {
        $this->setConsoleService($consoleService);
        $this->setInputHandler($inputHandler);
        $this->setOutputHandler($outputHandler);
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
            $this->getOutputHandler()
                ->setConsoleService($this->getConsoleService())
                ->setIrcService($this->getIrcService());
            $this->getOutputHandler()->preform();
            $ircServerConnection = $this->getIrcService()->connectToIrcServer();
            while (false === feof($ircServerConnection)) {
                $inputFromServer = $this->getIrcService()->readFromIrcServer();
                $this->getInputHandler()->handle($inputFromServer);
                $this->getOutputHandler()->handle();
            }
        } catch (Exception $exception) {
            $this->getConsoleService()->writeToConsole('<error>' . $exception->getMessage() . '</error>');
        }
    }
}
