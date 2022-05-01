<?php
namespace Everest\core\entities;

use Everest\core\exception\TestFailException;
use Everest\Tester;
use Exception;
use ReflectionClass;
use ReflectionNamedType;

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

	protected function assertNotArrayHasKey(mixed $value, array $array, string $message = '') : void
	{
		if (in_array($value, $array))
			throw new TestFailException(empty($message) ? "They array contains value $value" : $message);
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

	protected function assertNotClassHasProperty(string $attribute, string $className, string $message = '') : void
	{
		$rc = new ReflectionClass($className);
		if ($rc->hasProperty($attribute))
			throw new TestFailException("The \"$className\e class contains a \"$attribute\" attribute");
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

	protected function assertNotArraySubset(array $subset, array $haystack, string $message = '')
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
				if (in_array($vvalue, $haystack[$key]))
					throw new TestFailException($message);
		}
		$this->tester->addAssertion();
	}

	protected function assertClassHasStaticAttribute(string $attributeName, string $className, string $message = '')
	{
		$rp = new \ReflectionProperty($className, $attributeName);
		if (!($rp && $rp->isStatic()))
			throw new TestFailException($message ? $message : "The class does not contains a $attributeName static attribute");
		$this->tester->addAssertion();
	}

	protected function assertNotClassHasStaticAttribute(string $attributeName, string $className, string $message = '')
	{
		try
		{
			$rp = new \ReflectionProperty($className, $attributeName);
		}
		catch (\Exception $exception)
		{
			$this->tester->addAssertion();
			return ;
		}
		if (($rp && $rp->isStatic()))
			throw new TestFailException($message ? $message : "The class contains a $attributeName static attribute");
		$this->tester->addAssertion();
	}

	protected function assertContains(mixed $needle, array $haystack, string $message = '')
	{
		if (!in_array($needle, $haystack))
			throw new TestFailException($message ? $message : "They haystack does not contains the needle value");
		$this->tester->addAssertion();
	}

	protected function assertNotContains(mixed $needle, array $haystack, string $message = '')
	{
		if (in_array($needle, $haystack))
			throw new TestFailException($message ? $message : "They haystack contains the needle value");
		$this->tester->addAssertion();
	}

	protected function assertAttributeContains(mixed $needle, object $instance, string $attribute, string $message = '')
	{
		$rc = new ReflectionClass($instance);
		foreach ($rc->getProperties() as $property)
		{
			if ($property->getName() === $attribute)
			{
				$property->setAccessible(1);
				if ($property->getType()->getName() !== "array")
					throw new Exception("The $attribute property must be an array");
				if (!in_array($needle, $property->getValue($instance)))
					throw new Exception("The $attribute property does not contains needle value");
				$this->tester->addAssertion();
				return ;
			}
		}
		throw new Exception("The " . $instance::class . "class does not contains $attribute attribute");	
	}

	protected function assertNotAttributeContains(mixed $needle, object $instance, string $attribute, string $message = '')
	{
		$rc = new ReflectionClass($instance);
		foreach ($rc->getProperties() as $property)
		{
			if ($property->getName() === $attribute)
			{
				$property->setAccessible(1);
				if ($property->getType()->getName() !== "array")
					throw new Exception("The $attribute property must be an array");
				if (in_array($needle, $property->getValue($instance)))
					throw new Exception("The $attribute property contains needle value");
				$this->tester->addAssertion();
				return ;
			}
		}
		throw new Exception("The " . $instance::class . "class does not contains $attribute attribute");
	}

	//TODO: use isNative in the function
	protected function assertContainsOnly(string $type, array $haystack, bool $isNative = null, string $message = '')
	{
		foreach ($haystack as $element)
		{
			if (gettype($element) !== $type)
				throw new Exception("One element is not a $type type");
		}
		$this->tester->addAssertion();
	}

	protected function assertNotContainsOnly(string $type, array $haystack, bool $isNative = null, string $message = '')
	{
		$typeCount = 0;
		foreach ($haystack as $element)
		{
			if (gettype($element) === $type)
				$typeCount++;
		}
		if ($typeCount === count($haystack))
			throw new Exception("They array contains only $type values");
		$this->tester->addAssertion();
	}

	protected function assertCount(int $expectedCount, array $haystack, string $message = '')
	{
		if ($expectedCount !== count($haystack))
			throw new Exception($message ? $message : "Haystack count must be equal to $expectedCount");
		$this->tester->addAssertion();
	}

	protected function assertNotCount(int $expectedCount, array $haystack, string $message = '')
	{
		if ($expectedCount === count($haystack))
			throw new Exception($message ? $message : "Haystack count is equal to $expectedCount");
		$this->tester->addAssertion();
	}

	protected function assertDirectoryExists(string $directory, string $message = '')
	{
		if (!is_dir($directory))
		{
			if (!is_file($directory))
				throw new Exception("Specified directory $directory does not exists");
			else
				throw new Exception("Specified directory $directory is a file");
		}
		$this->tester->addAssertion();
	}

	protected function assertNotDirectoryExists(string $directory, string $message = '')
	{
		if (is_dir($directory))
			throw new Exception($message ? $message : "Specified directory IS a directory (it must not)");
		$this->tester->addAssertion();
	}

	protected function assertDirectoryReadable(string $directory, string $message = '')
	{
		if (!is_readable($directory))
			throw new Exception($message ? $message : "Specified directory must be readable");
		$this->tester->addAssertion();
	}

	protected function assertNotDirectoryReadable(string $directory, string $message = '')
	{
		if (is_readable($directory))
			throw new Exception($message ? $message : "Specified directory is readable");
		$this->tester->addAssertion();
	}

	protected function assertDirectoryWriteable(string $directory, string $message = '')
	{
		if (!is_writeable($directory))
			throw new Exception($message ? $message : "Specified directory must be writeable");
		$this->tester->addAssertion();
	}

	protected function assertNotDirectoryWriteable(string $directory, string $message = '')
	{
		if (is_writeable($directory))
			throw new Exception($message ? $message : "Specified directory is writeable");
		$this->tester->addAssertion();
	}

	protected function assertEmpty(mixed $value, string $message = '')
	{
		if (!empty($value))
			throw new Exception($message ? $message : "Value must be empty");
		$this->tester->addAssertion();
	}

	protected function assertNotEmpty(mixed $value, string $message = '')
	{
		if (empty($value))
			throw new Exception($message ? $message : "Value is empty");
		$this->tester->addAssertion();
	}

	protected function assertEquals(mixed $expected, mixed $value, string $message = '')
	{
		if ($expected !== $value)
			throw new Exception($message ? $message : "Expected value must be equal to \$value");
		$this->tester->addAssertion();
	}

	protected function assertNotEquals(mixed $expected, mixed $value, string $message = '')
	{
		if ($expected === $value)
			throw new Exception($message ? $message : "Expected value is equal to \$value");
		$this->tester->addAssertion();
	}

	protected function assertFalse(bool $condition, string $message = '')
	{
		if ($condition)
			throw new Exception($message ? $message : "Condition must be false");
		$this->tester->addAssertion();
	}

	protected function assertNotFalse(bool $condition, string $message = '')
	{
		if (!$condition)
			throw new Exception($message ? $message : "Condition is false");
		$this->tester->addAssertion();
	}

	protected function assertFileExists(string $filename, string $message = '')
	{
		if (!file_exists($filename))
			throw new Exception($message ? $message : "Filename must exists");
		$this->tester->addAssertion();
	}

	protected function assertNotFileExists(string $filename, string $message = '')
	{
		if (file_exists($filename))
			throw new Exception($message ? $message : "Filename exists");
		$this->tester->addAssertion();
	}

	protected function assertGreaterThan(mixed $expected, mixed $actual, string $message = '')
	{
		if (!($actual > $expected))
			throw new Exception($message ? $message : "\$actual value must be greater than expected value");
		$this->tester->addAssertion();
	}

	protected function assertNotGreaterThan(mixed $expected, mixed $actual, string $message = '')
	{
		if ($actual > $expected)
			throw new Exception($message ? $message : "\$actual is greater than expected value");
		$this->tester->addAssertion();
	}

	protected function assertGreaterThanOrEqual(mixed $expected, mixed $actual, string $message = '')
	{
		if (!($actual >= $expected))
			throw new Exception($message ? $message : "\$actual value must be greater or equal to expected");
		$this->tester->addAssertion();	
	}

	protected function assertInstanceOf(object $expected, string $actual, string $message = '')
	{
		if (!($expected instanceof $actual))
			throw new Exception($message ? $message : "Expected value must be an instance of $actual");
		$this->tester->addAssertion();
	}

	protected function assertNotInstanceOf(object $expected, string $actual, string $message = '')
	{
		if ($expected instanceof $actual)
			throw new Exception($message ? $message : "Expected value is instance of actual $actual");
		$this->tester->addAssertion();
	}

	protected function assertLessThan(mixed $expected, mixed $actual, string $message = '')
	{
		if (!($actual < $expected))
			throw new Exception($message ? $message : "Actual value must be less than expected value");
		$this->tester->addAssertion();
	}

	protected function assertLessThanOrEqual(mixed $expected, mixed $actual, string $message = '')
	{
		if (!($actual <= $expected))
			throw new Exception($message ? $message : "Actual value must be less or equal to expected");
		$this->tester->addAssertion();
	}

	protected function assertNull(mixed $variable, string $message = '')
	{
		if (!is_null($variable))
			throw new Exception($message ? $message : "The given variable must be null");
		$this->tester->addAssertion();
	}

	protected function assertNotNull(mixed $variable, string $message = '')
	{
		if (is_null($variable))
			throw new Exception($message ? $message : "The given variable is null");
		$this->tester->addAssertion();
	}

	protected function assertTrue(mixed $condition, string $message = '')
	{
		if (!$condition)
			throw new Exception($message ? $message : "The given condition must be true");
		$this->tester->addAssertion();
	}

	protected function assertNotTrue(mixed $condition, string $message = '')
	{
		if ($condition)
			throw new Exception($message ? $message : "The given condition is true");
		$this->tester->addAssertion();
	}

	protected function assertSame(mixed $expected, mixed $actual, string $message = '')
	{
		if (gettype($actual) !== gettype($expected))
			throw new Exception("Both values must be the same type");
		if ($actual !== $expected)
			throw new Exception($message ? $message : "Both values must be equals");
		$this->tester->addAssertion();
	}

	protected function assertNotSame(mixed $expected, mixed $actual, string $message = '')
	{
		if ((gettype($actual) === gettype($expected)) || ($actual === $expected))
			throw new Exception($message ? $message : "Both values have same types or values");
		$this->tester->addAssertion();
	}

	protected function assertStringStartsWith(string $needle, string $haystack, string $message = '')
	{
		if (strpos($haystack, $needle) !== 0)
			throw new Exception($message ? $message : "Haystack string must begin with needle value");
		$this->tester->addAssertion();
	}

	protected function assertStringStartsNotWith(string $needle, string $haystack, string $message = '')
	{
		if (strpos($haystack, $needle) === 0)
			throw new Exception($message ? $message : "Haystack starts with needle value");
		$this->tester->addAssertion();
	}
}

?>