<?php
namespace Everest\core\entities;

use ReflectionClass;

// https://phpunit.readthedocs.io/fr/latest/assertions.html#assertarrayhaskey
abstract class TestCase
{

	/**
	 * Test if a needle value is present in a haystack array
	 * 
	 * @param mixed $value
	 * @param array $array
	 * @param string $message
	 * @return TestResult
	 */
	protected function assertArrayHasKey(mixed $value, array $array, string $message = '') : TestResult
	{
		if (in_array($value, $array))
			return (TestResult::pass("The array contains '$value' !"));
		return (TestResult::fail(empty($message) ? "The array does not contains value '\e[1m$value\e[0m'." : $message));
	}

	/**
	 * Test if a class contains a specified property
	 * 
	 * @param string $attribute
	 * @param string $className
	 * @param string $message
	 */
	protected function assertClassHasProperty(string $attribute, string $className, string $message = '') : TestResult
	{
		$rc = new ReflectionClass($className);
		if (!empty($rc->hasProperty($attribute)))
			return (TestResult::pass("The '$className' class contains a '$attribute' attribute !"));
		return (TestResult::fail(empty($message) ? "The '\e[1m$className\e[0m' class doest not contains a '\e[1m$attribute\e[0m' attribute." : $message));
	}

	protected function assertArraySubset(array $subset, array $haystack, string $message = '')
	{
		if (!(count($subset) != 0 && count($haystack) != 0))
			return TestResult::fail($message);
		foreach ($subset as $key => $value)
		{
			if (is_array($value))
			{
				if (array_key_exists($key, $haystack))
				{
					foreach($value as $vvalue)
					{
						if (!in_array($vvalue, $haystack[$key]))
							return (TestResult::fail("ewew"));
					}
				}
				else
					return (TestResult::fail($message));
			}
			else
				return (TestResult::fail($message));
		}
		return (TestResult::pass("yay"));
	}
}

?>