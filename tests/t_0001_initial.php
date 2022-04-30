<?php
namespace EverestTests;
use Everest\core\entities\TestCase;

class t_0001_initial extends TestCase
{
	private string $testAttribute;

	public function __construct()
	{
		parent::__construct();
	}

	public function TestKeyPass()
	{
		$this->assertArrayHasKey(3, [1, 2, 3]);
	}

	public function TestKeyFail()
	{
		$this->assertArrayHasKey(3, [1, 2, 4]);
	}

	public function TestClassPropertyPass()
	{
		$this->assertClassHasProperty("testAttribute", self::class);
		$this->assertClassHasProperty("testAttribute", self::class);
	}

	public function TestClassPropertyFail()
	{
		$this->assertClassHasProperty("random", self::class);
	}

	public function TestArraySubsetPass()
	{
		$this->assertArraySubset(['test' => [1, 2]], ['test' => [1, 2, 3]], "pute");
	}

	public function TestArraySubsetFail()
	{
		$this->assertArraySubset(['test' => [1, 4]], ['test' => [1, 2, 3]]);
	}
}
?>