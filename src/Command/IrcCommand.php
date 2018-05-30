<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\IrcService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IrcCommand extends Command
{
    private $ircService;

    public function __construct(IrcService $ircService)
    {
        $this->ircService = $ircService;
        parent::__construct();
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
                $this->ircService->readFromServer();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
