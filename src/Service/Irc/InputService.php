<?php

declare(strict_types=1);

namespace App\Service\Irc;

use App\EventListener\IrcEventSubscriber;
use App\Exception\IrcException;
use App\Service\ConsoleService;
use App\Service\NickService;
use Symfony\Component\EventDispatcher\EventDispatcher;

class InputService
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var ConnectionService
     */
    private $ConnectionService;

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

    public function __construct(
        ConnectionService $ConnectionService,
        ConsoleService $consoleService,
        OutputService $outputService,
        NickService $nickService
    ) {
        $this->setConnectionService($ConnectionService);
        $this->setOutputService($outputService);
        $this->setConsoleService($consoleService);

        $eventSubscriber = new IrcEventSubscriber();
        $eventSubscriber->setOutputService($outputService);
        $eventSubscriber->setNickService($nickService);

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($eventSubscriber);
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
     * @return InputService
     */
    public function setOptions(array $options): InputService
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param array $options
     * @return InputService
     */
    public function addOptions(array $options): InputService
    {
        $this->setOptions(array_merge($this->getOptions(), $options));

        return $this;
    }

    /**
     * @return ConnectionService
     */
    public function getConnectionService(): ConnectionService
    {
        return $this->ConnectionService;
    }

    /**
     * @param ConnectionService $ConnectionService
     * @return InputService
     */
    public function setConnectionService(ConnectionService $ConnectionService): InputService
    {
        $this->ConnectionService = $ConnectionService;

        return $this;
    }

    /**
     * @return OutputService
     */
    public function getOutputService(): OutputService
    {
        return $this->outputService;
    }

    /**
     * @param OutputService $outputService
     * @return InputService
     */
    public function setOutputService(OutputService $outputService): InputService
    {
        $this->outputService = $outputService;

        return $this;
    }

    /**
     * @return ConsoleService
     */
    public function getConsoleService(): ConsoleService
    {
        return $this->consoleService;
    }

    /**
     * @param ConsoleService $consoleService
     * @return InputService
     */
    public function setConsoleService(ConsoleService $consoleService): InputService
    {
        $this->consoleService = $consoleService;

        return $this;
    }

    public function handle($input)
    {
        try {
            if ('' !== $input) {
                if (true === $this->getOptions()['verbose']) {
                    $this->getConsoleService()->writeToConsole($input);
                }
                if (':' === substr($input, 0, 1)) {
                    $this->colonInput($input);
                } else {
                    $this->nonColonInput($input);
                }
            }
        } catch (IrcException $exception) {
            $this->getConsoleService()->writeToConsole('<error>' . $exception->getMessage() . '</error>');
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
            $this->getOutputService()->output($output);
        }
        if (false !== strpos(strtoupper($input), 'ERROR')) {
            throw new IrcException($input);
        }
    }
}
