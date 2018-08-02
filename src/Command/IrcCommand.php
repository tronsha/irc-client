<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\BotService;
use App\Service\ConsoleService;
use App\Service\Irc\InputService;
use App\Service\Irc\NetworkService;
use App\Service\Irc\OutputService;
use App\Service\IrcService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IrcCommand extends ContainerAwareCommand
{
    /**
     * @var BotService
     */
    private $botService;

    /**
     * @var IrcService
     */
    private $ircService;

    /**
     * @var ConsoleService
     */
    private $consoleService;

    /**
     * @var InputService
     */
    private $inputService;

    /**
     * @var OutputService
     */
    private $outputService;

    /**
     * @var NetworkService
     */
    private $networkService;

    /**
     * IrcCommand constructor.
     * @param BotService $botService
     * @param IrcService $ircService
     * @param NetworkService $networkService
     * @param ConsoleService $consoleService
     * @param InputService $inputService
     * @param OutputService $outputService
     */
    public function __construct(
        BotService $botService,
        IrcService $ircService,
        NetworkService $networkService,
        ConsoleService $consoleService,
        InputService $inputService,
        OutputService $outputService
    ) {
        parent::__construct();
        $this->botService = $botService;
        $this->ircService = $ircService;
        $this->networkService = $networkService;
        $this->consoleService = $consoleService;
        $this->inputService = $inputService;
        $this->outputService = $outputService;
    }

    protected function configure()
    {
        $this->setName('irc:run');
        $this->addArgument('host', InputArgument::OPTIONAL, 'Server Host?');
        $this->addArgument('port', InputArgument::OPTIONAL, 'Server Port?');
        $this->addArgument('password', InputArgument::OPTIONAL, 'Server Password?');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->consoleService->setOutput($output);

            $this->networkService->setArguments($input->getArguments());
            $this->inputService->setOptions($input->getOptions());
            $this->outputService->setOptions($input->getOptions());
            $this->outputService->preform();
            $this->ircService->connectToIrcServer();
            $this->ircService->handleIrcInputOutput();

        } catch (Exception $exception) {
            $this->consoleService->writeToConsole('<error>' . $exception->getMessage() . '</error>');
        }
    }
}
