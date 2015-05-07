<?php

namespace Slash;

interface EventListenerInterface {
    function onRequest();

    function onResponse();

    function onFinished();

    function getSubscribedEvents();
} 