<?php

namespace Slash\Module;

use Slash\ClosureDispatcher;
use Slash\Router;

class AppModule extends AbstractModule {

	public function provides() {
		$this->providers['Slash\Router'] = new Router();

		$this->providers['Slash\ClosureDispatcher'] = new ClosureDispatcher();
	}
}