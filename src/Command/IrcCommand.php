<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ConsoleService;
use App\Service\IrcService;
use App\Service\Irc\InputService;
use App\Service\Irc\OutputService;
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
     * @var InputService
     */
    private $inputService;

    /**
     * @var OutputService
     */
    private $outputService;

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
        InputService $inputService,
        OutputService $outputService
    ) {
        $this->setConsoleService($consoleService);
        $this->setInputService($inputService);
        $this->setOutputService($outputService);
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
            $this->getInputService()
                ->setConsoleService($this->getConsoleService())
                ->setIrcService($this->getIrcService())
                ->setOptions($input->getOptions());
            $this->getOutputService()
                ->setConsoleService($this->getConsoleService())
                ->setIrcService($this->getIrcService());
            $this->getOutputService()->preform();
            $ircServerConnection = $this->getIrcService()->connectToIrcServer();
            while (false === feof($ircServerConnection)) {
                $inputFromServer = $this->getIrcService()->readFromIrcServer();
                $this->getInputService()->handle($inputFromServer);
                $this->getOutputService()->handle();
            }
        } catch (Exception $exception) {
            $this->getConsoleService()->writeToConsole('<error>' . $exception->getMessage() . '</error>');
        }
    }
}
