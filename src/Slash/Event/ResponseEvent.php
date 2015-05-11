<?php

namespace Slash\Event;

use Slash\Http\Request;
use Slash\Http\Response;

class ResponseEvent extends Event {

    private $response;

    public function __construct(Request $request, Response $response) {
        parent::__construct($request);

        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse(Response $response) {
        $this->response = $response;

        return $this;
    }
} 