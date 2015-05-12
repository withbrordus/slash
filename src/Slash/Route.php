<?php

namespace Slash;


class Route {

	private $uri;

	private $action;

	private $methods;

	private $caseSensitive;

	private $requirements;

	public function __construct($uri, $callable, $caseSensitive = false) {
		$this->setUri($uri);
		$this->setAction($callable);
		$this->caseSensitive = $caseSensitive;
		$this->requirements = [];
		$this->methods = ['*'];
	}

	public function getUri() {
		return $this->uri;
	}

	public function setUri($uri) {
		$this->uri = $uri;
	}

	public function getAction() {
		return $this->action;
	}

	public function setAction($callable) {
		$matches = array();

		if(is_string($callable) && preg_match('!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!', $callable, $matches)) {
			$class = $matches[0];
			$method = $matches[1];
			$callable = function() use($class, $method) {
				static $object;

				if($object === null) {
					$object = new $class;
				}

				return call_user_func_array(array($object, $method), func_get_args());
			};
		}

		if(!is_callable($callable)) {
			throw new \InvalidArgumentException('Route action must be callable!');
		}

		$this->action = $callable;
	}

	public function method($methods) {
		$this->methods = is_array($methods) ? $methods : array($methods);

		return $this;
	}

	public function matches($requestURI, $method) {
		if(defined('APP_FILE_PATH')) {
            $requestURI = trim(str_replace(APP_FILE_PATH, '', $requestURI));

            if($requestURI == '' || strlen($requestURI) == 0) {
                $requestURI = '/';
            }
        }

        if(substr($this->uri, -1) === '/') {
			$this->uri .= "?";
		}

		$regexURI = preg_replace_callback("/{[\w]+}\+?/", function($matches) {
			$param = substr($matches[0], 1, strlen($matches[0])-2);

			return "(?P<{$param}>\w+)";
		}, $this->uri);

		$regexURI = "#^{$regexURI}$#";

		$regexURI = $this->caseSensitive === true ? $regexURI : $regexURI.'i';

		preg_match($regexURI, $requestURI, $matches, PREG_OFFSET_CAPTURE);

		if((bool)$matches && array_search($method, $this->getMethods()) !== false) {
			$this->setRequirements($requestURI, $regexURI);

			return true;
		}

		return false;
	}

	public function setRequirements($requestURI, $regex) {
		$requirements = [];

		preg_match_all("/\{([A-Za-z0-9 ]+?)\}/", $this->uri, $matches);

		if(sizeof($matches) > 0) {
			foreach($matches[0] as $match) {
				$requirements[] = substr($match, 1, strlen($match)-2);
			}
		}

		$closureReflect = new \ReflectionFunction($this->action);

		$parameterReflect = [];
		foreach($closureReflect->getParameters() as $parameter) {
			$parameterReflect[] = $parameter->getName();
		}

		preg_match($regex, $requestURI, $matches, PREG_OFFSET_CAPTURE);

		$index = 0;
		foreach($requirements as $requirement) {
			if($requirement != $parameterReflect[$index]) {
				throw new \InvalidArgumentException("Required parameter {$requirement}");
			}

			$this->requirements[$requirement] = $matches[$requirement][0];

			$index++;
		}

	}

	public function getMethods() {
		return $this->methods;
	}

	public function getRequirements() {
		return $this->requirements;
	}
} 