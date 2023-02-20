<?php

class TestClass implements MyInterface {
	public function __construct(
		public readonly string $v
	) {

	}
}