<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Service\Irc\OutputService;
use App\Service\IrcService;
use App\Service\NickService;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IrcEventSubscriber implements EventSubscriberInterface
{
    private static $subscribedEvents = null;

    private $ircService;
    private $outputService;
    private $nickService;

    public function setIrcService($ircService): IrcEventSubscriber
    {
        $this->ircService = $ircService;

        return $this;
    }

    public function getIrcService(): IrcService
    {
        return $this->ircService;
    }

    public function setOutputService(OutputService $outputService): IrcEventSubscriber
    {
        $this->outputService = $outputService;

        return $this;
    }

    public function getOutputService(): OutputService
    {
        return $this->outputService;
    }

    public function setNickService(NickService $nickService): IrcEventSubscriber
    {
        $this->nickService = $nickService;

        return $this;
    }

    public function getNickService(): NickService
    {
        return $this->nickService;
    }

    public function handle(Event $event)
    {
        $event->setIrcService($this->getIrcService());
        $event->setOutputService($this->getOutputService());
        $event->setNickService($this->getNickService());
        $event->handle();
    }

    public static function loadSubscribedEvents()
    {
        $ircEventDirectory = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Event' . DIRECTORY_SEPARATOR . 'Irc');
        $ircEventFiles = glob($ircEventDirectory . DIRECTORY_SEPARATOR . 'On*.php');
        foreach ($ircEventFiles as $ircEventFile) {
            $name = lcfirst(str_replace([$ircEventDirectory . DIRECTORY_SEPARATOR , '.php'], '', $ircEventFile));
            self::$subscribedEvents[$name] = 'handle';
        }
    }

    public static function getSubscribedEvents()
    {
        if (null === self::$subscribedEvents) {
            self::loadSubscribedEvents();
        }

        return self::$subscribedEvents;
    }
}
