<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GenerateUnitTesting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:test {type} {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $unitTestHelper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $file=$this->argument('file');
        $type=$this->argument('type');
        $this->unitTestHelper=new UnitTestHelper($file);
        $this->unitTestHelper->addNameSpace('tests');
        $this->unitTestHelper->addRequires($file);
        $this->unitTestHelper->addUses();
        $this->unitTestHelper->addClassname();
        $this->unitTestHelper->addClassObject();
        $this->unitTestHelper->addProperties();
        $this->unitTestHelper->addSetUp();
        $this->unitTestHelper->addConstructedObject();
        $this->unitTestHelper->addTearDownMethod();
        $this->unitTestHelper->addMethods();
        file_put_contents('tests/'.$this->unitTestHelper->class->name->name.'Test.php',$this->unitTestHelper->output);
        $this->info($this->unitTestHelper->output);
    }
}

/*what us ast? why used ast? data flow or not using ast?
    relative studies, why it is better?
    testing accuracy
user experience from 4-5 php developers*/
