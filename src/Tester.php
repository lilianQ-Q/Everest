<?php
namespace Everest;

use Everest\core\entities\TestResult;

class Tester
{
	protected static $instance;

	protected array $testDirectories = [];

	private array $tests = [];

	public array $testPassed = [];

	public array $testFailed = [];

	private bool $stopOnFailure = false;

	public static function instance() : Tester
	{
		if (is_null(static::$instance))
			static::$instance = new Tester();
		return (static::$instance);
	}

	/**
	 * Adds a new directory to test
	 * 
	 * @param string $namespace
	 * @param string $path
	 */
	public function directory(string $namespace, string $path)
	{
		$this->testDirectories[$namespace] = $path;
		return ($this);
	}

	/**
	 * Returns all scanned test files in directories array
	 * 
	 * @param void
	 * @return array
	 */
	private function scanDir()
	{
		$tmp = [];
		foreach ($this->testDirectories as $namespace => $folder)
		{
			$tmp[$namespace] = array_map(function(string $element) use ($namespace)
					{
						return ("$namespace\\" . basename($element));
					}, glob("$folder/t_*.php"));
		}
		return ($tmp); 
	}

	public function runTestClass(string $classname) : array
	{
		$testResults = [];
		$namespace = explode("\\", $classname)[0];
		/**
		 * @var \Everest\core\entities\TestCase $instance
		 */
		$instance = new $classname();
		$rc = new \ReflectionClass($instance);
		foreach ($rc->getMethods() as $method)
		{
			if (strpos($method->name, "Test") === 0 && $method->isPublic())
				$testResults[$namespace][$method->name] = $method->invoke($instance);
		}
		return ($testResults);
	}

	private function sortResults(array $testResults) : void
	{
		foreach ($testResults as $namespace => $className)
		{
			/**
			 * @var \Everest\core\entities\TestResult $testResult
			 */
			foreach ($className as $testName => $testResult)
			{
				if ($testResult->isOk())
					$this->testPassed[$namespace][$testName] = $testResult;
				else
					$this->testFailed[$namespace][$testName] = $testResult;
			}
		}
	}

	public function run() : void
	{
		$files = $this->scanDir();
		foreach ($files as $namespace => $files)
		{
			foreach ($files as $file)
			{
				$file = substr($file, 0, -4);
				$this->tests = $this->runTestClass($file);
				$this->sortResults($this->tests);
			}
		}
		$this->printResults();
	}

	private function getTestsNamespaces() : array
	{
		return (array_keys($this->testDirectories));
	}

	public function printResults()
	{
		foreach ($this->getTestsNamespaces() as $namespace)
		{
			$tmp = [];
			$testsCount = count($this->testPassed[$namespace]) + count($this->testFailed[$namespace]);

			echo "$namespace : " . realpath($this->testDirectories[$namespace]) . "\n";
			foreach ($this->testPassed[$namespace] as $testName => $testResult)
			{
				$tmp[$testName] = $testResult;
			}
			foreach ($this->testFailed[$namespace] as $testName => $testResult)
			{
				$tmp[$testName] = $testResult;
			}
			/**
			 * @var \Everest\core\entities\TestResult $testResult
			 */
			foreach ($tmp as $testName => $testResult)
			{
				echo "\t$testName " . ($testResult->isOk() ? "OK" : "NON") . "\n";
			}
			echo "\n(tests $testsCount)\n\n";
		}
	}
}

?>