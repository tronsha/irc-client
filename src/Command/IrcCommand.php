<?php

namespace App\Command;

use App\Service\IrcService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IrcCommand extends Command
{
    public function __construct(IrcService $irc)
    {
        $this->irc = $irc;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('irc:run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->irc->run($output);
    }
}
