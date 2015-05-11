<?php

namespace Slash\Event;

use Slash\Event\Dispatcher\EventDispatcherInterface;
use Slash\Http\Request;

class Event {
    private $request;

    private $eventName;

    private $dispatcher;

    private $stopEventPropagation = false;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function hasRequest() {
        return $this->request !== null;
    }

    public function getRequest() {
        return $this->request;
    }

    public function setRequest(Request $request) {
        $this->request = $request;
    }

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