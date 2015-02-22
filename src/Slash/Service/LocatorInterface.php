<?php

namespace Slash\Service;

interface LocatorInterface extends \ArrayAccess, \Countable, \IteratorAggregate {

	static function create();

	public function setDefaults($items);

	public function set($key, $value);

	public function get($key, $default = null);

	public function has($key);

	public function remove($key);

	public function singleton($key, $value);

	public function protect(\Closure $closure);
} 