<?php

namespace Slash\Event;

use Slash\Event\Dispatcher\EventDispatcherInterface;

class Event {
    private $eventName;

    private $dispatcher;

    private $stopEventPropagation = false;

    public function setDispatcher(EventDispatcherInterface $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    public function getDispatcher() {
        return $this->dispatcher;
    }

    public function isEventPropagationStopped() {
        return $this->stopEventPropagation == true;
    }

    public function stopEventPropagation() {
        if(!$this->isEventPropagationStopped()) {
            $this->stopEventPropagation = true;
        }
    }

    public function setEventName($eventName) {
        $this->eventName = $eventName;
    }
} 