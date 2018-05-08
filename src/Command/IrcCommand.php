<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IrcCommand extends Command
{
    protected function configure()
    {
        $this->setName('irc:run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}