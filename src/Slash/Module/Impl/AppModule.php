<?php

namespace Slash\Module\Impl;

use Slash\ClosureDispatcher;
use Slash\Module\ModuleProviderInterface;
use Slash\Router;
use Slash\Service\LocatorInterface;

class AppModule implements ModuleProviderInterface {

	public function provides(LocatorInterface $locator) {
		$locator->set('Slash\Router', function() {
			return new Router();
		});

		$locator->set('Slash\ClosureDispatcher', function() {
			return new ClosureDispatcher();
		});
	}

	function boot() {

	}
}