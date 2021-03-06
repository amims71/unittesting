<?php
namespace App\Models;

class Person extends User
{
    protected $name;
    protected $name2;

    public function __construct(string $name){
        $this->name = $name;
    }

    public function greeting(){
        return "Hello {$this->name}";
    }

    public function getName($name){
        echo $name;
        return $name;
    }

    public function setName( $name, $name2): void{
        $this->name = $name;
        $this->name2 = $name2;
    }
    public function add($x,$y):int{
        return $x+$y;
    }
}
