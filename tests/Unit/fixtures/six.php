<?php declare (strict_types = 1);

abstract class FOO {
	public string $a = 'b';
	public static float $b = 0.0;
	abstract protected function zim();
	final public static function bar() {

	}
}

class Cache extends BaseConfig {

}

class Cache extends BaseConfig implements B {

}