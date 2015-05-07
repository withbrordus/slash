<?php

namespace Slash;

interface EventDispatcherInterface {

    function dispatch($listeners, $eventName, Event $event);

    function addListener(EventListenerInterface $listener);

    function removeListener(EventListenerInterface $listener);
}