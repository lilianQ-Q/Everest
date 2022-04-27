<?php

use Everest\Tester;

require_once(__DIR__ . '/../src/libs/autoloader.php');

$tester = Tester::instance();

$tester->directory("EverestTests", __DIR__ . "/../tests")
		->directory("EverestTests2", __DIR__ . "/../test2")
		->run();
?>