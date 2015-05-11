<?php

namespace Slash\Event\Listener\Impl;

use Slash\Event\Events;
use Slash\Event\Listener\EventSubscriberInterface;
use Slash\Event\RequestEvent;
use Slash\Event\ResponseEvent;

class RouterListener implements EventSubscriberInterface {

    function onRequest(RequestEvent $event) {
        echo 'Request Listener!';
    }

    function onResponse(ResponseEvent $event) {
        echo 'Response Listener!';
    }

    function getSubscribedEvents() {
        return [
            Events::REQUEST => ['onRequest', 1],
            Events::RESPONSE => ['onResponse', 1]
        ];
    }
}