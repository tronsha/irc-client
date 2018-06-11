<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IrcEventSubscriber implements EventSubscriberInterface
{
    private static $subscribedEvents = null;

    public function handle(Event $event)
    {
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
