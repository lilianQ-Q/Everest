<?php
namespace Everest\core\entities;

class TestResult
{
	public bool $result;

	public string $message;

	private function __construct(bool $result, string $errorMessage = '')
	{
		$this->result = $result;
		$this->message = $errorMessage;
	}

	/**
	 * Creates a new valid TestResult
	 * 
	 * @param string $message
	 */
	public static function pass(string $message = '') : TestResult
	{
		return (new TestResult(true, $message ?? 'Assertion succeed'));
	}

	/**
	 * Creates a new unvalid TestResult
	 */
	public static function fail(string $message = '') : TestResult
	{
		return(new TestResult(false, $message ?? 'Assertion failed'));
	}

	/**
	 * Returns a boolean to tell if the test passed or not
	 * 
	 * @param void
	 * @return bool
	 */
	public function isOk() : bool
	{
		return ($this->result);
	}
}
?>