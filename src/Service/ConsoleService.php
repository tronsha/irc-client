<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleService
{
    /**
     * @var OutputInterface
     */
    private $consoleOutput;

    /**
     * ConsoleService constructor.
     */
    public function __construct()
    {
        $this->consoleOutput = new ConsoleOutput();
    }

    /**
     * @param string $text
     */
    public function writeToConsole(string $text)
    {
        $this->consoleOutput->writeln([
            $text,
        ]);
    }
}
