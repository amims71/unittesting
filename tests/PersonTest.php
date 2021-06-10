<?php
namespace test;


        
use App\Models\Person;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase{
    protected $person;
    protected $name;
    protected $name2;
        
    protected function setUp(): void
    {
        parent::setUp();
        $this->name=''; //TODO set test value
        $this->name2=''; //TODO set test value
        $this->person = new Person($this->name);
    }
        
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->person);
        unset($this->name);
        unset($this->name2);
    }

    public function testGreeting(): void
    {
        $expected = '';//TODO set test value
        
        $this->assertSame($expected, $this->person->greeting());
    }

    public function testGetName(): void
    {
        $expected = '';//TODO set test value
        
        $this->assertSame($expected, $this->person->getName());
    }

public function testSetName(): void
    {
        $expected = '';//TODO set test value
        $property = (new ReflectionClass(Person::class))
            ->getProperty('name');
        $property->setAccessible(true);
        $this->person->setName($expected);
        $this->assertSame($expected, $property->getValue($this->person));
    }

    public function testAdd(): void
    {
        $expected = '';//TODO set test value
        $x=''; //TODO set test value
        $y=''; //TODO set test value
        
        $this->assertSame($expected, $this->person->add($x,$y));
    }

}
