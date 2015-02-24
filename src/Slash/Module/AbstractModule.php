<?php

namespace Slash\Module;

abstract class AbstractModule {

	protected $providers;

	public abstract function provides();

	public function getProviders() {
		return $this->providers;
	}

} 