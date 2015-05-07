<?php

namespace Slash;

use Slash\Module\Impl\AppModule;
use Slash\Module\Impl\TwigModule;
use Slash\Module\ModuleProviderInterface;
use Slash\Service\Locator;
use Slash\Service\LocatorInterface;
use Slash\Http\Request;
use Slash\Http\Response;
use Twig_Environment;

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

	private $modules;

    private $rootPath = null;

	public function __construct(array $userSettings = [], array $modules = []) {
		$this->request = Request::createFromGlobals();

		$this->locator = Locator::create();

		$this->locator->set('settings', $userSettings + self::defaultSettings());

		$this->settings = $this->locator->get('settings');

		$this->modules = [
			'Slash\Module\AppModule' => new AppModule(),
			'Slash\Module\TwigModule' => new TwigModule($this->settings['template.path'])
		] + $modules;

		$this->runConfiguration();

		$this->router = $this->locator->get('Slash\Router');

		$this->dispatcher = $this->locator->get('Slash\ClosureDispatcher');

	}

	public static function defaultSettings() {
		return [
			'app.environment' => Slash::DEV,
			'app.debug' => true,
			'route.caseSensitive' => false,
			'template.path' => __DIR__
		];
	}

	public function runConfiguration() {
		if($this->settings['app.debug']) {
			Debug::enabled();
		}

		/** @var $module ModuleProviderInterface */
		foreach($this->modules as $module) {
			if($module instanceof ModuleProviderInterface) {
				$module->provides($this->locator);

				$module->boot();
			}
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

	public function rootRoute($rootPath, ControllerProviderInterface $controller) {
        $this->rootPath = $rootPath;

        if($controller->connect($this)) {
            $this->rootPath = null;
        }
	}

    public function clearRootPath() {
        $this->rootPath = null;
    }

	public function route(array $args) {
		$uri = array_shift($args);
        $callable = array_pop($args);

        if($this->rootPath != null) {
            if(strrpos($this->rootPath, '/') > -1 && strlen($this->rootPath) !== 1) {
                $this->rootPath = rtrim($this->rootPath, '/');

                $uri = ltrim($uri, '/');
            }

            $uri = sprintf('%s/%s', $this->rootPath, $uri);

            $this->clearRootPath();
        }

        $route = new Route($uri, $callable, $this->settings['route.caseSensitive']);
        $controller = new Controller($route);

        $this->router->lock($controller);

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

	public function render($template, array $model = []) {
		/** @var $renderer Twig_Environment */
		$renderer = $this->locator->get('renderer');

		return $renderer->render($template, $model);
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

			$response = $this->dispatcher->dispatch($action, $this->request, $response);

		} catch(\Exception $e) {
			if($e->getCode() == Response::NOT_FOUND) {
				$response->setHttpCode($e->getCode());
				$response->write($e->getMessage());
			} else {
				$response->setHttpCode(Response::INTERNAL_SERVER_ERROR);
				$response->write($e->getMessage());
			}
		}

		$response->flush();
	}
} 