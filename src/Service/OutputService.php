<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Console\Output\ConsoleOutput;

class OutputService
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    /**
     * @param string $text
     */
    public function write(string $text)
    {
        $this->output->writeln([
            $text,
        ]);
    }
}
