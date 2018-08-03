<?php

declare(strict_types=1);

namespace App\Service\Irc;

use App\EventListener\IrcEventSubscriber;
use App\Exception\IrcException;
use App\Service\BotService;
use App\Service\ConsoleService;
use Symfony\Component\EventDispatcher\EventDispatcher;

class InputService
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var BotService
     */
    private $botService;

    /**
     * @var ConnectionService
     */
    private $connectionService;

    /**
     * @var OutputService
     */
    private $outputService;

    /**
     * @var ConsoleService
     */
    private $consoleService;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * InputService constructor.
     *
     * @param ConnectionService $connectionService
     * @param ConsoleService    $consoleService
     * @param OutputService     $outputService
     */
    public function __construct(
        ConnectionService $connectionService,
        ConsoleService $consoleService,
        OutputService $outputService
    ) {
        $this->connectionService = $connectionService;
        $this->outputService = $outputService;
        $this->consoleService = $consoleService;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return InputService
     */
    public function setOptions(array $options): InputService
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return InputService
     */
    public function addOptions(array $options): InputService
    {
        $this->setOptions(array_merge($this->getOptions(), $options));

        return $this;
    }

    /**
     * @param BotService $botService
     */
    public function setBotService(BotService $botService)
    {
        $this->botService = $botService;
        $this->initEvents();
    }

    public function initEvents()
    {
        $eventSubscriber = new IrcEventSubscriber();
        $eventSubscriber->setBotService($this->botService);

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($eventSubscriber);
    }

    /**
     * @param $input
     *
     * @throws \Exception
     */
    public function handle($input)
    {
        try {
            if ('' !== $input) {
                if (true === $this->getOptions()['verbose']) {
                    $time = (new \DateTime('now', new \DateTimeZone('Europe/Berlin')))->format('Y-m-d H:i:s ');
                    $this->consoleService->writeToConsole(
                        '<timestamp>' . $time . '</timestamp>' .
                        '<input>' . $this->consoleService->prepare(
                            $input,
                            true,
                            null,
                            true,
                            true,
                            strlen($time)
                        ) . '</input>'
                    );
                }
                if (':' === substr($input, 0, 1)) {
                    $this->colonInput($input);
                } else {
                    $this->nonColonInput($input);
                }
            }
        } catch (IrcException $exception) {
            $this->consoleService->writeToConsole('<error>' . $exception->getMessage() . '</error>');
        }
    }

    /**
     * @param string $input
     */
    private function colonInput($input)
    {
        preg_match(
            "/^\:(?:([^\!\ \:]+)\!)?([^\!\ ]+)\ ([^\ ]+)(?:\ ([^\:].*?))?(?:\ \:(.*?))?(?:\r)?$/i",
            $input,
            $data
        );
        $eventName = 'on' . strtoupper($data[3]);
        $class = '\\App\\Event\\Irc\\' . ucfirst($eventName);
        if (true === class_exists($class)) {
            $event = new $class($data);
            $this->dispatcher->dispatch($eventName, $event);
            unset($event);
        }
    }

    /**
     * @param string $input
     *
     * @throws IrcException
     */
    private function nonColonInput($input)
    {
        if (false !== strpos(strtoupper($input), 'PING')) {
            $output = str_replace('PING', 'PONG', $input);
            $this->outputService->output($output);
        }
        if (false !== strpos(strtoupper($input), 'ERROR')) {
            throw new IrcException($input);
        }
    }
}
