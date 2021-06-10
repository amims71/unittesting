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
        $this->output.='
namespace '.$nameSpace.';

';
    }

    public function addRequires($file){
        $this->output.='require \'vendor/autoload.php\';
require \''.$file.'\';';
    }

    public function addUses(){
        $this->output.='
        
use '.implode('\\',$this->object->name->parts).'\\'.$this->class->name->name.';
use ReflectionClass;
use PHPUnit\Framework\TestCase;

';
    }

    public function addClassname(){
        $this->output.='class '.$this->class->name->name.'Test extends TestCase';
    }

    public function addClassObject(){
        $this->output.='{
    protected $'.lcfirst($this->class->name->name).';';
    }

    public function addProperties(){
        foreach ($this->properties as $property){
            $this->output.='
    protected $'.$property->props[0]->name->name.';';
        }
    }
    public function addSetUp(){
        $this->output.='
        
    protected function setUp(): void
    {
        parent::setUp();';
        foreach ($this->properties as $property){
            $this->output.='
        $this->'.$property->props[0]->name->name.'=\'\'; //TODO set test value';
        }
    }

    public function addConstructedObject(){
        $this->output.='
        $this->'.lcfirst($this->class->name->name).' = new '.$this->class->name->name.'('.$this->constructorParams.');
    }';
    }

    public function addTearDownMethod(){
        $this->output.='
        
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->'.lcfirst($this->class->name->name).');';
        foreach ($this->properties as $property){
            $this->output.='
        unset($this->'.$property->props[0]->name->name.');';
        }

        $this->output.='
    }

';
    }

    public function addMethods(){
        $testMethods='';

        foreach ($this->methods as $method){
            $prprty='';
            $asrt='';
            if (@$method->returnType->name=='void'){
                $prprty='$this->'.lcfirst($this->class->name->name).'->'.$method->name->name.'($expected);';
                $asrt='$this->assertSame($expected, $property->getValue($this->'.lcfirst($this->class->name->name).'));';
                $testMethods.='public function test'.ucfirst($method->name->name).'(): void
    {
        $expected = \'\';//TODO set test value
        $property = (new ReflectionClass('.$this->class->name->name.'::class))
            ->getProperty(\'name\');
        $property->setAccessible(true);
        '.$prprty.'
        '.$asrt.'
    }

';
            } else{
                $params=$method->params;
                $paramNames=[];
                $prprty='';
                if (sizeof($params)>0) {
                    foreach($params as $param){
                        $paramName='$'.$param->var->name;
                        array_push($paramNames,$paramName);
                        $prprty.=$paramName.'=\'\'; //TODO set test value
        ';
                    }
                }
                $paramNames=implode(',', $paramNames);
                $asrt='$this->assertSame($expected, $this->'.lcfirst($this->class->name->name).'->'.$method->name->name.'('.$paramNames.'));';
                $testMethods.='    public function test'.ucfirst($method->name->name).'(): void
    {
        $expected = \'\';//TODO set test value
        '.$prprty.'
        '.$asrt.'
    }

';
            }

        }
        $this->output.=$testMethods.'}
';
    }


}
