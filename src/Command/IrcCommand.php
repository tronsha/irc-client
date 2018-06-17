<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Send;
use App\Service\Irc\InputHandler;
use App\Service\IrcService;
use App\Service\ConsoleService;
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

            $doctrine = $this->getContainer()->get('doctrine');
            $sendRepository = $doctrine->getRepository(Send::class);
            $send = $sendRepository->getSend();
//            $sendRepository->remove($send);
//            $sendRepository->flush();


            var_dump($send);
            die;

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
