<?php
namespace EverestTests;
use Everest\core\entities\TestCase;
use Everest\core\entities\TestResult;
use Everest\core\exception\TestFailException;
use Everest\Tester;

class t_0001_initial extends TestCase
{
	private string $testAttribute;

	private static string $hello;

	private array $arraytest = ["hey", "this is", "test"];

	public function __construct()
	{
		parent::__construct();
	}

	public function TestArrayHasKey()
	{
		$this->assertArrayHasKey(3, [1, 2, 3]);
	}

	public function TestArrayHasNotKey()
	{
		$this->assertNotArrayHasKey(4, [1, 2, 3]);
	}

	public function TestClassHasProperty()
	{
		$this->assertClassHasProperty("testAttribute", self::class);
	}

	public function TestClassHasNotProperty()
	{
		$this->assertNotClassHasProperty("boogi", self::class);
	}

	public function TestArraySubset()
	{
		$this->assertArraySubset(['test' => [1, 2]], ['test' => [1, 2, 3]], "pute");
	}

	public function TestNotArraySubset()
	{
		$this->assertNotArraySubset(['test' => [1, 2]], ['test' => [4, 5, 6]], "pute");
	}

	public function TestClassHasStaticAttribute()
	{
		$this->assertClassHasStaticAttribute("hello", self::class);
	}

	public function TestNotClassHasStaticAttribute()
	{
		$this->assertNotClassHasStaticAttribute("dontexist", self::class);
	}

	public function TestContains()
	{
		$this->assertContains("needle", ["blah", 21, "Blih", "needle", 42]);
	}

	public function TestNotContains()
	{
		$this->assertNotContains("needle", ["blah", 21, "Blih", 42]);
	}

	public function TestAttributeContains()
	{
		$test = new t_0001_initial();
		$this->assertAttributeContains("test", $test, "arraytest");
	}

	public function TestNotAttributeContains()
	{
		$test = new t_0001_initial();
		$this->assertNotAttributeContains("poom", $test, "arraytest");
	}

	public function TestContainsOnly()
	{
		$tmp = [1, 45, 3];
		$this->assertContainsOnly("integer", $tmp);
	}

	public function TestNotContainsOnly()
	{
		$tmp = [1, 'ez', 3];
		$this->assertNotContainsOnly("integer", $tmp);
	}

	public function TestCount()
	{
		$tmp = ["one", "two", "three"];
		$this->assertCount(3, $tmp);
	}

	public function TestNotCount()
	{
		$tmp = ["four", "five", "six"];
		$this->assertNotCount(4, $tmp);
	}

	public function TestDirectoryExists()
	{
		$this->assertDirectoryExists(__DIR__ . "/../src");
	}

	public function TestNotDirectoryExists()
	{
		$this->assertNotDirectoryExists(__DIR__ . "/../useless");
	}

	public function TestDirectoryReadable()
	{
		$this->assertDirectoryReadable(__DIR__ . "/../src");
	}

	public function TestNotDirectoryReadable()
	{
		$this->assertNotDirectoryReadable(__DIR__ . "/../useless");
	}

	public function TestDirectoryWriteable()
	{
		$this->assertDirectoryWriteable(__DIR__ . "/../src");
	}

	public function TestNotDirectoryWriteable()
	{
		$this->assertNotDirectoryWriteable(__DIR__ . "/../useless");
	}

	public function TestAssertEmpty()
	{
		$array = [];
		$string = '';
		$int = 0;
		$null = null;
		$this->assertEmpty($array);
		$this->assertEmpty($string);
		$this->assertEmpty($int);
		$this->assertEmpty($null);
	}

	public function TestAssertNotEmpty()
	{
		$array = ['ezez'];
		$string = 'ezez';
		$int = 1;
		$null = true;
		$this->assertNotEmpty($array);
		$this->assertNotEmpty($string);
		$this->assertNotEmpty($int);
		$this->assertNotEmpty($null);
	}

	public function TestAssertEquals()
	{
		$this->assertEquals([1, 2], [1, 2]);
		$this->assertEquals(null, null);
		$this->assertEquals(1, 1);
		$this->assertEquals("Pouet", "Pouet");
	}

	public function TestAssertNotEquals()
	{
		$this->assertNotEquals([1, 3], [1, 2]);
		$this->assertNotEquals(null, true);
		$this->assertNotEquals(1, 2);
		$this->assertNotEquals("Pouet", "Pouetyo");
	}

	public function TestAssertFalse()
	{
		$this->assertFalse(1 === 2);
		$this->assertFalse([1, 1] === [1, 2]);
		$this->assertFalse("pouet" === "ah!");
	}

	public function TestAssertNotFalse()
	{
		$this->assertNotFalse(1 === 1);
		$this->assertNotFalse([1, 1] === [1, 1]);
		$this->assertNotFalse("pouet" === "pouet");
	}

	public function TestAssertFileExists()
	{
		$this->assertFileExists(__DIR__ . "/../config/autoloader.json");
	}

	public function TestAssertNotFileExists()
	{
		$this->assertNotFileExists(__DIR__ . "/../config/ptdr.json");
	}

	public function TestAssertGreaterThan()
	{
		$this->assertGreaterThan(1, 56);
	}

	public function TestAssertNotGreaterThan()
	{
		$this->assertNotGreaterThan(56, 1);
	}

	public function TestAssertGreaterThanOrEqual()
	{
		$this->assertGreaterThanOrEqual(56, 56);
		$this->assertGreaterThanOrEqual(56, 100);
	}

	public function TestAssertInstanceOf()
	{
		$test = new t_0001_initial();
		$this->assertInstanceOf($test, self::class);
	}

	public function TestAssertNotInstanceOf()
	{
		$test = new t_0001_initial();
		$this->assertNotInstanceOf($test, "Everest\\zebi");
	}

	public function TestAssertLessThan()
	{
		$this->assertLessThan(50, 40);
	}

	public function TestAssertLessThanOrEqual()
	{
		$this->assertLessThanOrEqual(50, 50);
	}

	public function TestAssertNull()
	{
		$this->assertNull(null);
	}

	public function TestAssertNotNull()
	{
		$this->assertNotNull("null");
	}
	
	public function TestAssertTrue()
	{
		$this->assertTrue(1 === 1);
		$this->assertTrue([1, 1] === [1, 1]);
		$this->assertTrue("string" === "string");
	}

	public function TestNotAssertTrue()
	{
		$this->assertNotTrue(1 === 2);
		$this->assertNotTrue([1, 1] === [1, 2]);
		$this->assertNotTrue("string" === "slipdebain");
	}

	public function TestAssertSame()
	{
		$instance = new TestFailException("ez");
		$this->assertSame($instance, $instance);
		$this->assertSame("oui", "oui");
	}

	public function TestAssertNotSame()
	{
		$instance = new TestFailException("no");
		$this->assertNotSame($instance, "\$instance");
		$this->assertNotSame("oui", 1);
	}

	public function TestAssertStringStartsWith()
	{
		$this->assertStringStartsWith("Salut, ", "Salut, jespere que tu vas bien.");
	}

	public function TestAssertStringStartsNotWith()
	{
		$this->assertStringStartsNotWith("Salut, ", "Yo les gens c lilian");
	}
}
?>