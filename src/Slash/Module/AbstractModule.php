<?php

namespace Slash\Module;

abstract class AbstractModule {

	protected $providers;

	public function getProviders() {
		return $this->providers;
	}

} 