<?php

$this->mock(Foo::class, function ($m) {
    return $m->shouldReceive('foo');
});

(new ClassParser)->parse();

// commment

(new ClassParser)->parse();

/** commment **/

(new ClassParser)->parse();
#[SetUp]
class A
{}

(new ClassParser)->parse();
(new ClassParser)->parse();
foreach ($a as $b) {(new ClassParser)->parse();}

helper() - 1;
helper() /** comment **/ - 1;

$a()();

(function ($b) {(new ClassParser)->parse();});

(call());
(call());
(call())(call());