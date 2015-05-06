<?php

namespace Slash\Module\Impl;

use Slash\Module\ModuleProviderInterface;
use Slash\Service\LocatorInterface;

class TwigModule implements ModuleProviderInterface {

	private $renderer;

	public function __construct($templatePath) {
		\Twig_Autoloader::register(false);

		$loader = new \Twig_Loader_Filesystem($templatePath);
		$renderer = new \Twig_Environment($loader);

		$this->renderer = $renderer;
	}

	public function provides(LocatorInterface $locator) {
		$locator->set('renderer', function() {
			return $this->renderer;
		});
	}

	function boot() {

	}
}