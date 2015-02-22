<?php

namespace Slash\Http;

class Response {

	const OK = 200;
	const CREATED = 201;
	const ACCEPTED = 202;
	const NON_AUTHORITATIVE_INFORMATION = 203;
	const NO_CONTENT = 204;
	const MULTIPLE_CHOICES = 300;
	const MOVED_PERMANENTLY = 301;
	const FOUND = 302;
	const SEE_OTHER = 303;
	const NOT_MODIFIED = 304;
	const USE_PROXY = 305;
	const RESERVED = 306;
	const TEMPORARY_REDIRECT = 307;
	const PERMANENTLY_REDIRECT = 308;
	const BAD_REQUEST = 400;
	const UNAUTHORIZED = 401;
	const PAYMENT_REQUIRED = 402;
	const FORBIDDEN = 403;
	const NOT_FOUND = 404;
	const METHOD_NOT_ALLOWED = 405;
	const NOT_ACCEPTABLE = 406;
	const PROXY_AUTHENTICATION_REQUIRED = 407;
	const REQUEST_TIMEOUT = 408;
	const INTERNAL_SERVER_ERROR = 500;
	const NOT_IMPLEMENTED = 501;
	const BAD_GATEWAY = 502;
	const SERVICE_UNAVAILABLE = 503;
	const GATEWAY_TIMEOUT = 504;
	const VERSION_NOT_SUPPORTED = 505;
	const NETWORK_AUTHENTICATION_REQUIRED = 511;

	public static $codes = array(
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		511 => 'Network Authentication Required',
	);


	private $httpVersion;

	private $httpCode;

	private $headers;

	private $body;

	public function __construct($httpCode = Response::OK, array $headers = [], $httpVersion = 'HTTP/1.1') {
		$this->httpCode = $httpCode;
		$this->headers = $headers;
		$this->httpVersion = $httpVersion;
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function getHeader($key) {
		if(isset($this->headers[$key])) {
			return $this->headers[$key];
		}
	}

	public function setHeaders(array $headers) {
		$this->headers = $headers;

		return $this;
	}

	public function setHeader($key, $value) {
		$this->headers[$key] = $value;

		return $this;
	}

	public function setHttpCode($code) {
		if(!isset(self::$codes[$code])) {
			throw new \InvalidArgumentException("Http code {$code} is invalid!");
		}

		$this->httpCode = $code;
	}

	public function getContentType() {
		return $this->getHeader('Content-Type');
	}

	public function setContentType($type = 'application/octet-stream') {
		if($this->getContentType() == null) {
			$this->setHeader('Content-Type', $type);
		}

		$this->headers['Content-Type'] = $type;
	}

	public function getLocation() {
		return $this->getHeader('Location');
	}

	public function setLocation($location) {
		$this->setHeader('Location', $location);
	}

	public function redirect($location) {
		$this->setLocation($location);

		$this->setHttpCode(Response::MOVED_PERMANENTLY);
	}

	public function write($content) {
		$this->body .= $content;

		return $this;
	}

	public function flush() {
		header("{$this->httpVersion} {$this->httpCode} ".self::$codes[$this->httpCode]);

		foreach($this->headers as $key => $header) {
			header($key.":".$header);
		}

		echo $this->body;
	}
}