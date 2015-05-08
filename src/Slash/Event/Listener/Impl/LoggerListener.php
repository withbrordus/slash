<?php

namespace Slash\Event\Listener\Impl;

use Slash\Event\Events;
use Slash\Event\Listener\EventSubscriberInterface;

class LoggerListener implements EventSubscriberInterface {

    function onRequest() {
        echo 'Request fire up!';
    }

    function onResponse() {
        return 'Response fire up!';
    }

    function getSubscribedEvents() {
        return [
            Events::REQUEST => ['onRequest', 1],
            Events::RESPONSE => ['onResponse', 1]
        ];
    }
}