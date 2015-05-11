<?php

namespace Slash;

use Slash\Event\Dispatcher\EventDispatcherInterface;
use Slash\Event\Events;
use Slash\Event\ResponseEvent;
use Slash\Http\Request;
use Slash\Http\Response;

class ClosureDispatcher {

	public function dispatch(\Closure $action, Request $request, Response $response, EventDispatcherInterface $eventDispatcher) {
		$response->write($action($request, $response));

        /** @var $responseEvent ResponseEvent */
        $responseEvent = $eventDispatcher->dispatch(Events::RESPONSE, new ResponseEvent($request, $response));
        $response = $responseEvent->getResponse();

		return $response;
	}
} 