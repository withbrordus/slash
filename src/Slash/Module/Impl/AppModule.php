<?php

namespace Slash\Module\Impl;

use Slash\ClosureDispatcher;
use Slash\Event\Dispatcher\Impl\EventDispatcher;
use Slash\Event\Listener\Impl\LoggerListener;
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

        $locator->set('Slash\Event\Dispatcher\Impl\EventDispatcher', function() {
            $dispatcher = new EventDispatcher();
            $dispatcher->addSubscriber(new LoggerListener());

            return $dispatcher;
        });
	}

	function boot() {

	}
}