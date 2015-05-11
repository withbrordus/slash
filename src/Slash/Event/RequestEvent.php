<?php

namespace Slash\Event;

use Slash\Http\Request;

class RequestEvent extends Event {

    public function __construct(Request $request) {
        parent::__construct($request);
    }
} 