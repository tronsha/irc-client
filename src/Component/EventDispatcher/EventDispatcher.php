<?php

declare(strict_types=1);

namespace App\Component\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher AS SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class EventDispatcher extends SymfonyEventDispatcher
{
    public function dispatch($eventName, Event $event = null, $data = null)
    {
        $class = '\\App\\Event\\Irc\\' . ucfirst($eventName);
        if (true === class_exists($class)) {
            $event = new $class($data);
            $result = parent::dispatch($eventName, $event);
            unset($event);
            return $result;
        }
        return null;
    }
}
