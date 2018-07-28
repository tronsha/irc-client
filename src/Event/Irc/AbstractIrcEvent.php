<?php

namespace App\Event\Irc;

use App\Service\BotService;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractIrcEvent extends Event
{
    protected $data;

    private $botService;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param BotService $botService
     *
     * @return Event
     */
    public function setBotService(BotService $botService): Event
    {
        $this->botService = $botService;

        return $this;
    }

    /**
     * @return BotService
     */
    public function getBotService(): BotService
    {
        return $this->botService;
    }

    public function handle()
    {
        $method = lcfirst(str_replace('App\\Event\\Irc\\', '', get_class($this)));
        if (true === method_exists($this, $method)) {
            $this->$method();
        }
    }
}
