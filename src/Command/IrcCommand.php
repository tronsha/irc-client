<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\IrcService;
use App\Service\ConsoleService;
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
            $ircServerConnection = $this->ircService->connectToServer();
            while (false === feof($ircServerConnection)) {
                $inputFromServer = $this->ircService->readFromServer();
                if (true === is_string($inputFromServer) && '' !== $inputFromServer) {
                    $inputFromServer = trim($inputFromServer);
                    $this->consoleService->write($inputFromServer);
                    if (':' !== substr($inputFromServer, 0, 1)) {
                        if (false !== strpos(strtoupper($inputFromServer), 'PING')) {
                            $output = str_replace('PING', 'PONG', $inputFromServer);
                            $this->ircService->writeToServer($output);
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
