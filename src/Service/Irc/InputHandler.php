<?php

declare(strict_types=1);

namespace App\Service\Irc;

use App\EventListener\IrcEventSubscriber;
use App\Exception\IrcException;
use App\Service\ConsoleService;
use App\Service\IrcService;
use Symfony\Component\EventDispatcher\EventDispatcher;

class InputHandler
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var IrcService
     */
    private $ircService;

    /**
     * @var ConsoleService
     */
    private $consoleService;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new IrcEventSubscriber());
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
     * @return InputHandler
     */
    public function setOptions(array $options): InputHandler
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param array $options
     * @return InputHandler
     */
    public function addOptions(array $options): InputHandler
    {
        $this->setOptions(array_merge($this->getOptions(), $options));

        return $this;
    }

    /**
     * @return IrcService
     */
    public function getIrcService(): IrcService
    {
        return $this->ircService;
    }

    /**
     * @param IrcService $ircService
     * @return InputHandler
     */
    public function setIrcService(IrcService $ircService): InputHandler
    {
        $this->ircService = $ircService;

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
     * @return InputHandler
     */
    public function setConsoleService(ConsoleService $consoleService): InputHandler
    {
        $this->consoleService = $consoleService;

        return $this;
    }

    public function handle($input)
    {
        try {
            if ('' !== $input) {
                if (':' === substr($input, 0, 1)) {
                    $this->colonInput($input);
                } else {
                    $this->nonColonInput($input);
                }

                if (true === $this->getOptions()['verbose']) {
                    $this->getConsoleService()->writeToConsole($input);
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
            $this->getIrcService()->writeToIrcServer($output);
        }
        if (false !== strpos(strtoupper($input), 'ERROR')) {
            throw new IrcException($input);
        }
    }
}
