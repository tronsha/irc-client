<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\IrcService;
use App\Service\ConsoleService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class IrcCommand extends Command
{
    private $consoleService;
    private $ircService;

    public function __construct(IrcService $ircService, ConsoleService $consoleService)
    {
        $this->consoleService = $consoleService;
        $this->ircService = $ircService;
        parent::__construct();
//        $this->dispatcher = new EventDispatcher();
//        $this->dispatcher->addSubscriber(new \App\EventListener\IrcEventSubscriber());
//        $this->dispatcher->dispatch('irc', new \App\Event\IrcEvent464('foo'));
    }

    protected function configure()
    {
        $this->setName('irc:run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $ircServerConnection = $this->ircService->connectToIrcServer();
            while (false === feof($ircServerConnection)) {
                $inputFromServer = $this->ircService->readFromIrcServer();
                if ('' !== $inputFromServer) {
                    $this->consoleService->writeToConsole($inputFromServer);
                    if (':' !== substr($inputFromServer, 0, 1)) {
                        if (false !== strpos(strtoupper($inputFromServer), 'PING')) {
                            $output = str_replace('PING', 'PONG', $inputFromServer);
                            $this->ircService->writeToIrcServer($output);
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
