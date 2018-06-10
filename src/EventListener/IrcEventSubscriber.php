<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IrcEventSubscriber implements EventSubscriberInterface
{
    private static $subscribedEvents = null;

    public function onIrcEvent(Event $event)
    {
        $event->handle();
    }

    public function __call($name, $arguments)
    {
        $arguments[0]->handle();
    }

    public static function loadSubscribedEvents()
    {
        $eventDirectory = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Event') . DIRECTORY_SEPARATOR;
        $ircEventFiles = glob($eventDirectory . 'IrcEventOn*.php');
        foreach ($ircEventFiles as $ircEventFile) {
            $name = lcfirst(str_replace([$eventDirectory . 'IrcEvent' , '.php'], '', $ircEventFile));
            self::$subscribedEvents[$name] = $name;
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
