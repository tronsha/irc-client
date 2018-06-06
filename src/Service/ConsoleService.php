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

    public function setOutput($output)
    {
        $this->consoleOutput = $output;

        return $this;
    }

    /**
     * @param string $text
     */
    public function writeToConsole(string $text)
    {
        $this->consoleOutput->writeln($text);
    }
}
