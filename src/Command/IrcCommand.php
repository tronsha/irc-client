<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ConsoleService;
use App\Service\IrcService;
use App\Service\Irc\InputHandler;
use App\Service\PreformService;
use App\Service\SendService;
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
     * @var InputHandler
     */
    private $inputHandler;

    /**
     * @var IrcService
     */
    private $ircService;

    /**
     * @var PreformService
     */
    private $preformService;

    /**
     * @var SendService
     */
    private $sendService;

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

    /**
     * @return PreformService
     */
    public function getPreformService(): PreformService
    {
        return $this->preformService;
    }

    /**
     * @param PreformService $preformService
     * @return IrcCommand
     */
    public function setPreformService(PreformService $preformService): IrcCommand
    {
        $this->preformService = $preformService;

        return $this;
    }

    /**
     * @return SendService
     */
    public function getSendService(): SendService
    {
        return $this->sendService;
    }

    /**
     * @param SendService $sendService
     * @return IrcCommand
     */
    public function setSendService(SendService $sendService): IrcCommand
    {
        $this->sendService = $sendService;

        return $this;
    }

    public function __construct(
        IrcService $ircService,
        ConsoleService $consoleService,
        PreformService $preformService,
        SendService $sendService,
        InputHandler $inputHandler
    ) {
        $this->setConsoleService($consoleService);
        $this->setInputHandler($inputHandler);
        $this->setIrcService($ircService);
        $this->setPreformService($preformService);
        $this->setSendService($sendService);
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
            $this->getPreformService()->preform();
            while (false === feof($ircServerConnection)) {
                $inputFromServer = $this->getIrcService()->readFromIrcServer();
                $this->getInputHandler()->handle($inputFromServer);
//                $this->getIrcService()->writeToIrcServer($this->getSendService()->getSend());
            }
        } catch (Exception $exception) {
            $this->getConsoleService()->writeToConsole('<error>' . $exception->getMessage() . '</error>');
        }
    }
}
