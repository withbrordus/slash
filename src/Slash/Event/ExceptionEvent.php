<?php

namespace Slash\Event;

use Slash\Http\Request;
use Slash\Http\Response;

class ExceptionEvent extends ResponseEvent {
    private $exception;

    public function __construct(Request $request, Response $response, \Exception $e) {
        parent::__construct($request, $response);

        $this->exception = $e;
    }

    public function getException() {
        return $this->exception;
    }
} 