<?php

class TestClass implements MyInterface {
	public function __construct(
		public readonly string $v
	) {

	}
}

class Service {
	private Logger $logger;

	public function __construct(
		Logger $logger = new NullLogger(),
	) {
		$this->logger = $logger;
	}
}

function redirect(string $uri): never {
	header('Location: ' . $uri);
	exit();
}