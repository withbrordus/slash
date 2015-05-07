<?php

namespace Slash;

class ControllerPool extends \ArrayObject {

    private $controllers;

    public function __construct() {
        $this->controllers = array();
    }

    public function addController(Controller $controller) {
        $this->controllers[] = $controller;

        return $this;
    }

    public function findNearest($uri) {

    }
} 