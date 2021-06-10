<?php
namespace App\Models;

class Person
{
    protected $name;
    protected $name2;

    public function __construct(string $name){
        $this->name = $name;
    }

    public function greeting(){
        return "Hello, I'm {$this->name}!";
    }

    public function getName(){
        return $this->name;
    }

    public function setName( $name, $name2): void{
        $this->name = $name;
        $this->name2 = $name2;
    }
    public function add($x,$y){
        return $x+$y;
    }
}
