<?php

namespace Slash\Module;

use Slash\Service\LocatorInterface;

interface ModuleProviderInterface {
	function provides(LocatorInterface $locator);

	function boot();
} 