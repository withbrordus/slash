<?php

namespace Slash;

use Slash\Service\Locator;
use Slash\Service\LocatorInterface;
use Slash\Http\Request;
use Slash\Http\Response;

class Slash {

	const PROD = "PRODUCTION";
	const DEV = "DEVELOPMENT";

	private $locator;

	private $request;

	private $errors;

	private $settings;

	/** @var $router Router */
	private $router;

	/** @var $router ClosureDispatcher */
	private $dispatcher;

	public function __construct(array $userSettings = []) {
		$this->locator = Locator::create();

		$this->locator->set('settings', $userSettings + self::defaultSettings());

		$this->request = Request::createFromGlobals();

		$this->locator->set('router', function() {
			return new Router();
		});

		$this->locator->set('dispatcher', function() {
			return new ClosureDispatcher();
		});

		$this->router = $this->locator->get('router');

		$this->dispatcher = $this->locator->get('dispatcher');

		$this->runConfiguration();
	}

	public static function defaultSettings() {
		return [
			'app.environment' => Slash::DEV,
			'app.debug' => true,
			'route.caseSensitive' => false
		];
	}

	public function runConfiguration() {
		$settings = $this->locator->get('settings');
		if($settings['app.debug']) {
			Debug::enabled();
		}
	}

	public function getLocator() {
		return $this->locator;
	}

	public function setInjector(LocatorInterface $injector) {
		$this->locator = $injector::create();
	}

	public function getRequest() {
		return $this->request;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function getSettings() {
		return $this->settings;
	}

	public function route(array $args) {
		$uri = array_shift($args);
		$callable = array_pop($args);

		$route = new Route($uri, $callable, $this->locator->get('settings')['route.caseSensitive']);

		$this->router->lock($route);

		return $route;
	}

	public function map() {
		$args = func_get_args();

		return $this->route($args);
	}

	public function get() {
		$args = func_get_args();

		return $this->route($args)->method([
			Request::GET, Request::HEAD
		]);
	}

	public function post() {
		$args = func_get_args();

		return $this->route($args)->method([
			Request::POST
		]);
	}

	public function put() {
		$args = func_get_args();

		return $this->route($args)->method([
			Request::PUT
		]);
	}

	public function patch() {
		$args = func_get_args();

		return $this->route($args)->method([
			Request::PATCH
		]);
	}

	public function delete() {
		$args = func_get_args();

		return $this->route($args)->method([
			Request::DELETE
		]);
	}

	public function render($template) {
		//support twig and blade engine
	}

	public function toJSON($data) {
		return json_encode($data);
	}

	public function run() {
		$response = new Response(Response::OK);

		try {
			$action = $this->router->resolve($this->request);

			if(empty($action) || $action == null) {
				throw new \Exception("Page not found!", Response::NOT_FOUND);
			}

			$this->dispatcher->dispatch($action, $this->request, $response);

		} catch(\Exception $e) {
			if($e->getCode() == Response::NOT_FOUND) {
				$response->setHttpCode($e->getCode());
				$response->write($e->getMessage());
			} else {
				$response->setHttpCode($e->getCode());
				$response->write($e->getMessage());
			}
		}

		$response->flush();
	}
} 