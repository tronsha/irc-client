<?php

declare(strict_types=1);

namespace App\Service\Irc;

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

    public function __construct()
    {
//        $this->dispatcher = new EventDispatcher();
//        $this->dispatcher->addSubscriber(new \App\EventListener\IrcEventSubscriber());
//        $this->dispatcher->dispatch('irc', new \App\Event\IrcEvent464('foo'));
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
        if ('' !== $input) {
            if (':' !== substr($input, 0, 1)) {
                if (false !== strpos(strtoupper($input), 'PING')) {
                    $output = str_replace('PING', 'PONG', $input);
                    $this->getIrcService()->writeToIrcServer($output);
                }
            }

            if (true === $this->getOptions()['verbose']) {
                $this->getConsoleService()->writeToConsole($input);
            }
        }
    }
}
