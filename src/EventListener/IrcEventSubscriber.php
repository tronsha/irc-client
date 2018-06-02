<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IrcEventSubscriber implements EventSubscriberInterface
{
    public function onIrcEvent(Event $event)
    {
        $event->run();
    }

    public static function getSubscribedEvents()
    {
        return [
            'irc' => 'onIrcEvent',
        ];
    }
}
