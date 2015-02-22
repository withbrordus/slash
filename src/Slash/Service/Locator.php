<?php

namespace Slash\Service;


class Locator implements LocatorInterface {

	private $data = array();

	public function __construct(array $items) {
		$this->setDefaults($items);
	}

	static function create($defaults = []) {
		return new self($defaults);
	}

	public function setDefaults($items) {
		foreach($items as $key => $item) {
			$this->set($key, $item);
		}
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function get($key, $default = null) {
		if($this->has($key)) {
			$invokable = is_object($this->data[$key]) && is_callable($this->data[$key]);

			return $invokable ? $this->data[$key]($this) : $this->data[$key];
		}

		return $default;
	}

	public function has($key) {
		return array_key_exists($key, $this->data);
	}

	public function remove($key) {
		unset($this->data[$key]);
	}

	public function singleton($key, $value) {
		$this->set($key, function($p) use($value) {
			static $object;

			if($object === null) {
				$object = $value($p);
			}

			return $object;
		});
	}

	public function protect(\Closure $closure) {
		return function() use($closure) {
			return $closure;
		};
	}

	/** Array Iterator Interface */
	public function getIterator() {
		return new \ArrayIterator($this->data);
	}

	/** Array Access Interface */
	public function offsetExists($offset) {
		return $this->has($offset);
	}


	public function offsetGet($offset) {
		return $this->get($offset);
	}


	public function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	public function offsetUnset($offset) {
		$this->remove($offset);
	}

	/** Countable Interface */
	public function count() {
		return count($this->data);
	}
}