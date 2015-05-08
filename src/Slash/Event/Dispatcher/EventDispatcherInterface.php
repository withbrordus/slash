<?php

namespace Slash\Event\Dispatcher;

use Slash\Event\Event;
use Slash\Event\Listener\EventSubscriberInterface;

interface EventDispatcherInterface {

    function dispatch($eventName, Event $event);

    function addListener($eventName, $listener, $priority = 0);

    function removeListener($eventName, $listener);

    function addSubscriber(EventSubscriberInterface $subscriber);

    function removeSubscriber(EventSubscriberInterface $subscriber);

}