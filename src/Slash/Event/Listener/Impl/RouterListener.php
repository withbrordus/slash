<?php

namespace Slash\Event\Listener\Impl;

use Slash\Event\Events;
use Slash\Event\Listener\EventSubscriberInterface;
use Slash\Event\ResponseEvent;
use Slash\Http\Response;

class RouterListener implements EventSubscriberInterface {

    function onRequest() {
        echo 'Request fire up!';
    }

    function onResponse($a, ResponseEvent $event) {
        $a = new Response();
        $a->write('response baru');

        $event->setResponse($a);
    }

    function getSubscribedEvents() {
        return [
            Events::REQUEST => ['onRequest', 1],
            Events::RESPONSE => ['onResponse', 1]
        ];
    }
}