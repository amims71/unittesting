<?php

namespace App\Console\Commands;


use PhpParser\Error;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\ParserFactory;

class UnitTestHelper{

    public $output='<?php';
    public $object;
    public $class;
    public $properties=[];
    public $methods=[];
    public $constructor='';
    public $constructorParams='';

    public function __construct($file){
        $code=file_get_contents($file);
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            $ast=$parser->parse($code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
        }
        $this->object=$ast[0];
        $this->class=$this->object->stmts[0];
        $statements=$this->class->stmts;
        foreach ($statements as $statement){
            if ($statement instanceof Property){
                array_push($this->properties,$statement);
            } elseif ($statement instanceof ClassMethod){
                if ($statement->name->name=='__construct') $this->constructor=$statement;
                else array_push($this->methods,$statement);
            }
        }


        foreach ($this->constructor->params as $param){
            $this->constructorParams.='$this->'.$param->var->name.',';
        }
        $this->constructorParams=substr($this->constructorParams, 0, -1);
    }

    public function addNameSpace($nameSpace){
        $this->output.=PHP_EOL.'namespace '.$nameSpace.';'.PHP_EOL.PHP_EOL;
    }

    public function addRequires($file){
        $this->output.='require \'vendor/autoload.php\';'.PHP_EOL.'require \''.$file.'\';';
    }

    public function addUses(){
        $this->output.=PHP_EOL.PHP_EOL.'use '.implode('\\',$this->object->name->parts).'\\'.$this->class->name->name.';'.PHP_EOL.'use ReflectionClass;'.PHP_EOL.'use PHPUnit\Framework\TestCase;'.PHP_EOL.PHP_EOL;
    }

    public function addClassname(){
        $this->output.='class '.$this->class->name->name.'Test extends TestCase';
    }

    public function addClassObject(){
        $this->output.='{'.PHP_EOL."\t".'protected $'.lcfirst($this->class->name->name).';';
    }

    public function addProperties(){
        foreach ($this->properties as $property){
            $this->output.=PHP_EOL."\t".'protected $'.$property->props[0]->name->name.';';
        }
    }
    public function addSetUp(){
        $this->output.=PHP_EOL.PHP_EOL."\t".'protected function setUp(): void'.PHP_EOL."\t".'{'.PHP_EOL."\t".'parent::setUp();';
        foreach ($this->properties as $property){
            $this->output.=PHP_EOL."\t\t".'$this->'.$property->props[0]->name->name.'=\'\'; //TODO set test value';
        }
    }

    public function addConstructedObject(){
        $this->output.="\n\t\t".'$this->'.lcfirst($this->class->name->name).' = new '.$this->class->name->name.'('.$this->constructorParams.');'."\n\t".'}';
    }

    public function addTearDownMethod(){
        $this->output.="\n\n\t".'protected function tearDown(): void'."\n\t".'{'."\n\t\t".'parent::tearDown();'."\n\n\t\t".'unset($this->'.lcfirst($this->class->name->name).');';
        foreach ($this->properties as $property){
            $this->output.="\n\t\t".'unset($this->'.$property->props[0]->name->name.');';
        }
        $this->output.="\n\t".'}'."\n\n";
    }

    public function addMethods($testMethods,$method){

        $prprty='';
        $asrt='';
        if (@$method->returnType->name=='void'){
            $params=$method->params;
            $paramNames=[];
            $getProp=[];
            $getAssert=[];
            if (sizeof($params)>0) {
                foreach($params as $key=>$param){
                    $paramName='$'.$param->var->name;
                    array_push($paramNames,$paramName);
                    $prprty.=$paramName.'=\'\'; //TODO set test value'."\n\t\t";
                    $prop="\n\t\t".'$property'.$key.' = (new ReflectionClass('.$this->class->name->name.'::class))'."\n\t\t\t".'->getProperty(\''.$param->var->name.'\');'."\n\t\t".'$property'.$key.'->setAccessible(true);';
                    array_push($getProp,$prop);
                    $asrt='$this->assertSame('.$paramName.', $property'.$key.'->getValue($this->'.lcfirst($this->class->name->name).'));'."\n\t\t";
                    array_push($getAssert,$asrt);
                }
            }
            $proprty='$this->'.lcfirst($this->class->name->name).'->'.$method->name->name.'('.implode(',', $paramNames).');';
            $testMethods.="\t".'public function test'.ucfirst($method->name->name).'(): void'."\n\t".'{'."\n\t\t".$prprty."\n\t\t".$proprty."\n\t\t".implode('',$getProp)."\n\t\t".implode('',$getAssert)."\n\t".'}'."\n\n";
        } else{
            $params=$method->params;
            $paramNames=[];
            if (sizeof($params)>0) {
                foreach($params as $param){
                    $paramName='$'.$param->var->name;
                    array_push($paramNames,$paramName);
                    $prprty.=$paramName.'=\'\'; //TODO set test value'."\n\t\t";
                }
            }
            $paramNames=implode(',', $paramNames);
            $asrt='$this->assertSame($expected, $this->'.lcfirst($this->class->name->name).'->'.$method->name->name.'('.$paramNames.'));';
            $testMethods.="\t".'public function test'.ucfirst($method->name->name).'(): void'."\n\t".'{'."\n\t\t".'$expected = \'\';//TODO set test value'."\n\t\t".$prprty."\n\t\t".$asrt."\n\t".'}'."\n\n";
        }
        return $testMethods;
    }
    public function closeClass(){
        $this->output.='}';
    }


}
