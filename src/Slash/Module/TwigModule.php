<?php

namespace Slash\Module;

class TwigModule extends AbstractModule {

	private $renderer;

	public function __construct() {
		\Twig_Autoloader::register(false);

		$loader = new \Twig_Loader_Filesystem(__DIR__);
		$renderer = new \Twig_Environment($loader);

		$this->renderer = $renderer;
	}

	public function provides() {
		$this->providers['renderer'] = $this->renderer;
	}
}