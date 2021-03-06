<?php
namespace tests;

require 'vendor/autoload.php';
require 'app/Models/Person.php';

use App\Models\Person;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase{
	protected $person;
	protected $name;
	protected $name2;
	protected $fillable;
	protected $hidden;
	protected $casts;

	protected function setUp(): void
	{
	parent::setUp();
		$this->name=''; //TODO set test value
		$this->name2=''; //TODO set test value
		$this->fillable=''; //TODO set test value
		$this->hidden=''; //TODO set test value
		$this->casts=''; //TODO set test value
		$this->person = new Person($this->name);
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		unset($this->person);
		unset($this->name);
		unset($this->name2);
		unset($this->fillable);
		unset($this->hidden);
		unset($this->casts);
	}

	public function testGreeting(): void
	{
		$expected = '';//TODO set test value
		
		$this->assertSame($expected, $this->person->greeting());
	}

	public function testGetName(): void
	{
		$expected = '';//TODO set test value
		$name=''; //TODO set test value
		
		$this->assertSame($expected, $this->person->getName($name));
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

	public function testAdd(): void
	{
		$expected = '';//TODO set test value
		$x=''; //TODO set test value
		$y=''; //TODO set test value
		
		$this->assertSame($expected, $this->person->add($x,$y));
	}

	public function testTututut(): void
	{
		$expected = '';//TODO set test value
		$text=''; //TODO set test value
		
		$this->assertSame($expected, $this->person->tututut($text));
	}

}