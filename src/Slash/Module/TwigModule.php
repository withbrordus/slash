<?php

namespace Slash\Module;

class TwigModule extends AbstractModule implements ModuleInterface {

	private $renderer;

	public function __construct($templatePath) {
		\Twig_Autoloader::register(false);

		$loader = new \Twig_Loader_Filesystem($templatePath);
		$renderer = new \Twig_Environment($loader);

		$this->renderer = $renderer;
	}

	public function provides() {
		$this->providers['renderer'] = $this->renderer;
	}
}