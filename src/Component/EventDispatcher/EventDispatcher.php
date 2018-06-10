<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Component\EventDispatcher;

use App\Event\IrcEventOn372;
use Symfony\Component\EventDispatcher\EventDispatcher AS SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class EventDispatcher extends SymfonyEventDispatcher
{
    public function dispatch($eventName, Event $event = null, $data = null)
    {
        $event = new IrcEventOn372($data);
        return parent::dispatch($eventName, $event);
    }
}
