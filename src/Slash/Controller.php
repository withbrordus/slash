<?php

namespace Slash;

class Controller {

    private $route;

    public function __construct(Route $route) {
        $this->route = $route;
    }

    public function getRoute() {
        return $this->route;
    }
} 