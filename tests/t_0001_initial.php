<?php
namespace EverestTests;
use Everest\core\entities\TestCase;

class t_0001_initial extends TestCase
{
	public string $testAttribute = "";

	public function TestKeyPass()
	{
		return($this->assertArrayHasKey(3, [1, 2, 3]));
	}

	public function TestKeyFail()
	{
		return($this->assertArrayHasKey(3, [1, 2, 4]));
	}

	public function TestClassPropertyPass()
	{
		return ($this->assertClassHasProperty("testAttribute", self::class));
	}

	public function TestClassPropertyFail()
	{
		return ($this->assertClassHasProperty("random", self::class));
	}

	public function TestArraySubsetPass()
	{
		return ($this->assertArraySubset(['test' => [1, 2]], ['test' => [1, 2, 3]], "pute"));
	}

	public function TestArraySubsetFail()
	{
		return ($this->assertArraySubset(['test' => [1, 4]], ['test' => [1, 2, 3]]));
	}


}
?>