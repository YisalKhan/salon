<?php

namespace SLB_API\Helper;

class RequestHelper {

	public function getGetQueryValue($name) {
		return isset($_GET[$name]) ? $_GET[$name] : null;
	}

	public function getPostQueryValue($name) {
		return isset($_POST[$name]) ? $_POST[$name] : null;
	}

	public function getAccessToken() {
		return $this->getHeaderValue('Access-Token');
	}

	public function getHeaderValue($name) {
		$headers = $this->getAllHeaders();

		return isset($headers[$name]) ? $headers[$name] : null;
	}

	public function getRequestBody() {
		$args = file_get_contents('php://input');
		$args = json_decode($args, true);

		return $args;
	}

	public function getRequestMethod() {
		return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
	}

	public function getRequestScheme() {
		return isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
	}

	public function getHttpHost() {
		return $_SERVER['HTTP_HOST'];
	}

	public function getRequestUri() {
		return $_SERVER['REQUEST_URI'];
	}

	private function getAllHeaders()
	{
		$headers = array();
		foreach($_SERVER as $name => $value)
		{
			if(substr($name, 0, 5) == 'HTTP_')
			{
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}