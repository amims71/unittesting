<?php
namespace tests;

require 'vendor/autoload.php';
require 'app/Models/Person.php';

use App\Models\Person;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

class PersonUMLTest extends TestCase{
	protected $person;
	protected $name;
	protected $name2;

	protected function setUp(): void
	{
		parent::setUp();
		$this->name=''; //TODO set test value
		$this->name2=''; //TODO set test value
		$this->person = new Person($this->name,$this->name2);
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		unset($this->person);
		unset($this->name);
		unset($this->name2);
	}

	public function testGetName(): void
	{
		$expected = '';//TODO set test value

		$this->assertSame($expected, $this->person->getName());
	}

	public function testSetName(): void
	{
		$name=''; //TODO set test value
		$name2=''; //TODO set test value

		$this->person->setName($name,$name2);

		$property0 = (new ReflectionClass(Person::class))
			->getProperty('name');
		$property0->setAccessible(true);
		$property1 = (new ReflectionClass(Person::class))
			->getProperty('name2');
		$property1->setAccessible(true);
		$this->assertSame($name, $property0->getValue($this->person));
		$this->assertSame($name2, $property1->getValue($this->person));

	}

	public function testGreeting(): void
	{
		$expected = 'Hello ';//TODO set test value

		$this->assertSame($expected, $this->person->greeting());
	}

	public function testAdd(): void
	{
		$expected = 5;//TODO set test value
		$x=3; //TODO set test value
		$y=2; //TODO set test value

		$this->assertSame($expected, $this->person->add($x,$y));
	}

}
