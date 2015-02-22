<?php

namespace Slash\Http;

class Request extends \ArrayObject {

	/** Tipe Request */
	const GET       = 'GET';
	const POST      = 'POST';
	const PUT       = 'PUT';
	const DELETE    = 'DELETE';
	const PATCH     = 'PATCH';
	const HEAD      = 'HEAD';
	const OPTIONS   = 'OPTIONS';

	private $method;

	private $uri;

	private $httpVersion;

	private $headers;

	private $query;

	private $post;

	private $files;

	private $server;

	private $cookie;

	private $body;

	private $payload;

	public function __construct($method, $uri, $httpVersion, $headers, $query, $post, $files, $server, $cookie, $body, $payload) {
		parent::__construct(($post + $query), \ArrayObject::STD_PROP_LIST);

		$this->method = $method;
		$this->uri = $uri;
		$this->httpVersion = $httpVersion;
		$this->headers = $headers;
		$this->query = $query;
		$this->post = $post;
		$this->files = $files;
		$this->server = $server;
		$this->cookie = $cookie;
		$this->body = $body;
		$this->payload = $payload;
	}

	static function create(
		$method = Request::GET,
		$uri = "/",
		$httpVersion = "HTTP/1.1",
		$headers = [],
		$query = [],
		$post = [],
		$files = [],
		$server = [],
		$cookie = [],
		$body = null
	) {
		$payload = [];
		if(isset($headers['Content-Type'])) {
			$contentType = $headers["Content-Type"];

			if($contentType == 'application/json') {
				$payload = json_decode($body, true);
			}
		}

		return new Request($method, "/".trim(urldecode($uri), '/'), $httpVersion, $headers, $query, $post, $files, $server, $cookie, $body, $payload);
	}

	static function createFromGlobals() {
		$requestURI = $_SERVER['REQUEST_URI'];
		$uri = ($pos = strpos($requestURI, "?")) ? substr($requestURI, 0, $pos) : $requestURI;

		return self::create(
			$_SERVER['REQUEST_METHOD'],
			$uri,
			$_SERVER['SERVER_PROTOCOL'],
			getallheaders(),
			$_GET,
			$_POST,
			$_FILES,
			$_SERVER,
			$_COOKIE,
			file_get_contents('php://input')
		);
	}

	public function getMethod() {
		return $this->method;
	}

	public function getUri() {
		return $this->uri;
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function getHeader($key) {
		if(isset($this->headers[$key])) {
			return $this->headers[$key];
		}
	}

	public function getFiles() {
		return $this->files;
	}

	public function getFile($key) {
		if(isset($this->files[$key])) {
			return $this->files[$key];
		}
	}

	public function getLength() {
		return $this->getHeader('Content-Length');
	}

	public function getBody() {
		return $this->body;
	}

	public function getPayload() {
		return $this->payload;
	}

	public function isAjax() {
		return isset($this->server['HTTP_X_REQUEST_WITH'])
		and strtolower($this->server['HTTP_X_REQUEST_WITH']) === 'xmlhttprequest';
	}

	public function isXhr() {
		return $this->isAjax();
	}

	public function __toString() {
		return "{$this->method}\n\n
		{$this->uri}\n\n
		{$this->httpVersion}";
	}
}