<?php

class TestClass implements MyInterface {
	public function __construct(
		public readonly string $v
	) {

	}

	public function handle() {

		$foo = function () {
			alert("bar");
		};

		$foo = function () {
			match ($x) {
				$a => function () {
				}
			};
		};

		match ($x) {
			$a => function () {
			}
		};
		match ($x) {
			$a => 1
		};
	}
}