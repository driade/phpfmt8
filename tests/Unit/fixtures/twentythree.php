<?php

match ($x) {
	$a => 1,
	$b => function () {},
	default => true
};