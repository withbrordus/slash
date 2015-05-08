<?php

namespace Slash\Event\Dispatcher\Impl;

use Slash\Event\Dispatcher\EventDispatcherInterface;
use Slash\Event\Event;
use Slash\Event\Listener\EventSubscriberInterface;

class EventDispatcher implements EventDispatcherInterface{

    private $listeners;

    function dispatch($eventName, Event $event = null) {
        if($event == null) {
            $event = new Event();
        }

        $event->setEventName($eventName);
        $event->setDispatcher($this);

        $sortedListeners = $this->sortListeners($eventName);;
        foreach($sortedListeners as $listeners) {
            foreach($listeners as $listener) {
                call_user_func($listener, $eventName, $event);
            }
        }
    }

    function sortListeners($eventName) {

        return $this->listeners[$eventName];
    }

    function getListeners($eventName) {
        return $this->listeners[$eventName];
    }

    function addListener($eventName, $listener, $priority = 0) {
        $this->listeners[$eventName][$priority][] = $listener;

        return $this;
    }

    function removeListener($eventName, $listener) {
        foreach($this->listeners[$eventName] as $priority => $listeners) {
            if(($pos = array_search($listeners, $listener, true)) !== false) {
                unset($this->listeners[$eventName][$priority][$pos]);
            }
        }
    }

    function addSubscriber(EventSubscriberInterface $subscriber) {
        foreach($subscriber->getSubscribedEvents() as $eventName => $actionName) {
            if(is_string($actionName)) {
                $this->addListener($eventName, [$subscriber, $actionName]);
            } else if(is_array($actionName)) {
                $priority = array_key_exists(1, $actionName) ? $actionName[1] : 0;

                $this->addListener($eventName, [$subscriber, $actionName[0]], $priority);
            }
        }

        return $this;
    }

    function removeSubscriber(EventSubscriberInterface $subscriber) {
        foreach($subscriber->getSubscribedEvents() as $eventName => $actionName) {
            if(is_string($actionName)) {
                $this->removeListener($eventName, [$subscriber, $actionName]);
            } else if(is_array($actionName)) {
                $this->removeListener($eventName, [$subscriber, $actionName[0]]);
            }
        }

        return $this;
    }
}