<?php

enum EnumTest {
case A;
case B;
}

class A extends Enum {

}

class A implements Enum {

}

class Enum {

}

class Foo {
	final public const XX = "foo";
	public function bar((A&B) | null $entity) {
		return $entity;
	}
}