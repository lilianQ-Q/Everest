<?php
namespace Everest\core\entities;

use Everest\core\exception\TestFailException;
use Everest\Tester;
use ReflectionClass;

// https://phpunit.readthedocs.io/fr/latest/assertions.html#assertarrayhaskey
abstract class TestCase
{

	protected Tester $tester;

	protected function __construct()
	{
		$this->tester = Tester::instance();
	}

	/**
	 * Test if a needle value is present in a haystack array
	 * 
	 * @param mixed $value
	 * @param array $array
	 * @param string $message
	 * @return TestResult
	 */
	protected function assertArrayHasKey(mixed $value, array $array, string $message = '') : void
	{
		if (!in_array($value, $array))
			throw new TestFailException(empty($message) ? "They array doest not contains value $value" : $message);
		$this->tester->addAssertion();
	}

	/**
	 * Test if a class contains a specified property
	 * 
	 * @param string $attribute
	 * @param string $className
	 * @param string $message
	 */
	protected function assertClassHasProperty(string $attribute, string $className, string $message = '') : void
	{
		$rc = new ReflectionClass($className);
		if (empty($rc->hasProperty($attribute)))
			throw new TestFailException("The \"$className\e class does not contains a \"$attribute\" attribute");
		$this->tester->addAssertion();
	}

	protected function assertArraySubset(array $subset, array $haystack, string $message = '')
	{
		if (!(count($subset) != 0 && count($haystack) != 0))
			throw new TestFailException($message);

		foreach ($subset as $key => $value)
		{
			if (!is_array($value))
				throw new TestFailException($message);

			if (!array_key_exists($key, $haystack))
				throw new TestFailException($message);

			foreach ($value as $vvalue)
				if (!in_array($vvalue, $haystack[$key]))
					throw new TestFailException($message);
		}
		$this->tester->addAssertion();
	}
}

?>