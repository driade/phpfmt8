<?php declare (strict_types = 1);

$this->mock(function ($m) {
	return $m->handle();
});

try {

} catch (\Exception $e) {

}

(new A);

try {

} catch (\Exception $e) {

} finally {

}

(new A);

try {

} catch (\Exception $e) {

} finally {

}

class A {
	public function c() {
		try {

			foreach ($a as $b) {
			}

			(new A);

		} catch (LogicException $e) {
		}
	}
}

match ($a) {
	'a' => call1(),
	'b' => call2()
};

function () {

};

(call());

(new C)->handle();
(new C)->handle();

$a()();

call();