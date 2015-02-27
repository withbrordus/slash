<?php

namespace Slash;

use Slash\Http\Request;
use Slash\Http\Response;

class ClosureDispatcher {

	public function dispatch(\Closure $action, Request $request, Response $response) {
		$response->write($action($request, $response));

		return $response;
	}
} 