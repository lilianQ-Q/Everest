<?php
namespace Everest;

use Everest\core\exception\TestFailException;
use Everest\libs\Printer;
use ReflectionClass;

class Tester
{
	protected static $instance;

	protected array $testDirectories = [];

	private bool $stopOnFailure = false;

	private int $assertionCount = 0;

	private int $testCount = 0;

	private bool $allTestsPassed = true;

	private function __construct()
	{
		$this->printer = new Printer();
	}

	/**
	 * Returns the singleton of the class
	 * 
	 * @param void
	 * @return Tester
	 */
	public static function instance() : Tester
	{
		if (is_null(static::$instance))
			static::$instance = new Tester();
		return (static::$instance);
	}

	/**
	 * Increments the assertions counter
	 * 
	 * @param void
	 * @return void
	 */
	public function addAssertion() : void
	{
		$this->assertionCount++;
	}

	/**
	 * Increments the tests counter
	 * 
	 * @param void
	 * @return void
	 */
	public function addTest() : void
	{
		$this->testCount++;
	}

	/**
	 * Adds a new directory to test
	 * 
	 * @param string $namespace
	 * @param string $path
	 * @return Tester
	 */
	public function directory(string $namespace, string $path) : self
	{
		$this->testDirectories[$namespace] = realpath($path);
		return ($this);
	}

	/**
	 * Returns the path of all test files from given directories
	 * 
	 * @param void
	 * @return array
	 */
	private function getTestsFiles() : array
	{
		$files = [];
		foreach ($this->testDirectories as $directory)
		{
			$directory = realpath($directory);
			$namespace = array_keys($this->testDirectories, $directory)[0];
			$files[$namespace] = array_map(function ($element){
				return (substr($element, 0, -4));
			}, glob($directory . "/t_*.php"));
		}
		return ($files);
	}

	/**
	 * Execute and returns status for all tested methods
	 * from a given class
	 * 
	 * @param string $namespace is the namespace of the given class
	 * @param string $filePath is the path to the class
	 * @return array
	 */
	private function executeTests(string $namespace, string $filePath) : array
	{
		//TODO: Improve to handle multiple directory in one directory
		if (!array_key_exists($namespace, $this->testDirectories))
			throw new TestFailException("Namespace $namespace not defined when instantiate the tester");
		$class = explode('/', $filePath);
		$class = $class[count($class) - 1];
		$class = "$namespace\\$class";
		$methodsStatus = [];
		$rc = new ReflectionClass($class);
		foreach ($rc->getMethods() as $method)
		{
			if (strpos($method->getName(), "Test") === 0)
			{
				try
				{
					$method->invoke($rc->newInstance());
					$methodsStatus[$method->getName()] = true;
				}
				catch (\Exception $exception)
				{
					$methodsStatus[$method->getName()] = false;						
					if ($this->stopOnFailure)
						return ($methodsStatus);
				}
				$this->testCount++;
			}
		}
		return ($methodsStatus);
	}

	/**
	 * Starts all the tests from all the given directories and files
	 * 
	 * @param void
	 * @return void
	 */
	public function run() : void
	{
		$testsFiles = $this->getTestsFiles();
		$testsStatus = [];
		foreach ($testsFiles as $namespace => $files)
		{
			foreach ($files as $file)
			{
				$testsStatus = $this->executeTests($namespace, $file);
			}
			
			if(in_array(false, array_values($testsStatus)))
			{
				$this->printer->print("")->redBanner("FAIL");
				$this->allTestsPassed = false;
			}
			else
				$this->printer->print("")->greenBanner("PASS");

			$this->printer->print(" $namespace : " . realpath($this->testDirectories[$namespace]));
			foreach ($testsStatus as $name => $value)
			{
				$this->printer->output($value ? "✓ " : "✗ ")
					->print($value ? "\e[1;30m$name\e[0m" : "\e[0;31m$name\e[0m");
			}
		}

		$this->printer->print("");
		$this->allTestsPassed ? $this->printer->greenBanner("[OK]") : $this->printer->redBanner("[KO]");
		$this->printer->print(" (" . $this->testCount .  " tests, " . $this->assertionCount . " assertions)")->print("");
	}

}

?>