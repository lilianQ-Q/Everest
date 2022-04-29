<?php

use Everest\Tester;

require_once(__DIR__ . '/../src/libs/autoloader.php');

$tester = Tester::instance();

$tester->directory("EverestTests", __DIR__ . "/../tests")
		->run();
?>