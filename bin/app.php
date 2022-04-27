<?php

use EverestTests\t_0001_initial;

require_once(__DIR__ . '/../src/libs/autoloader.php');

$class = new t_0001_initial();
$rc = new ReflectionClass(t_0001_initial::class);
$time = microtime(true);
foreach ($rc->getMethods() as $method)
{
	if ($method->isPublic())
	{
		/**
		 * @var Everest\entities\TestResult $result
		 */
		$result = $method->invoke(new t_0001_initial());
		printf("[%s\e[39m]\t %s %s\n", $result->isOk() ? "\e[32mOK" : "\e[31mNO", $method->getName(), $result->isOk() ? "" : " -> " . $result->message);
	}
}
echo "\n================================\n";
echo "\t" . round(microtime(true) - $time, 4) . " seconds \n";
echo "================================\n";
?>