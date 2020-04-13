<?php


namespace WebAppId\LazyCrud;


use Carbon\Carbon;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 00.17
 * Class SmartMakeCommand
 * @package WebAppId\LazyCrud
 */
abstract class SmartMakeCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $inputName;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $classNameSuffix = '';

    /**
     * @var string
     */
    protected $stubFile;

    /**
     * @var string
     */
    protected $nameSpace;

    /**
     * @var bool
     */
    protected $isSubFolder = false;

    abstract function prevHandle();

    abstract function closeHandle();

    private function checkRequiredProperties()
    {
        if ($this->name == '') {
            $this->error('Please add protected name in the class command class or set on construct method');
        }
        if ($this->description == '') {
            $this->error('Please add protected property description on command class or set on construct method');
        }

        if ($this->stubFile == '') {
            $this->error('Please add protected property stubFile on command class or set on construct method');
        }

        if ($this->nameSpace == '') {
            $this->error('Please add protected property nameSpace on command class or set on construct method');
        }

    }

    public function handle()
    {
        $this->inputName = $this->getNameInput();

        $this->prevHandle();

        $this->checkRequiredProperties();

        $this->className = $this->inputName . $this->classNameSuffix;

        $name = $this->qualifyClass($this->className);

        $path = $this->getPath($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->className)) {
            $this->error($this->className . ' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->closeHandle();

        $this->info($this->className . ' created successfully.');

        return true;
    }

    abstract function replaceClassCustom(string $stub);

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {

        if (!$this->inputName) {
            throw new InvalidArgumentException("Missing required argument request name");
        }

        $stub = parent::replaceClass($stub, $this->inputName);

        $stub = str_replace('dummyClass', lcfirst($this->inputName), $stub);

        $date = Carbon::now(env('TZ', 'UTC'))->format('Y/m/d');

        $time = Carbon::now(env('TZ', 'UTC'))->format('H:i:s');

        $stub = $this->replaceClassCustom($stub);

        $stub = str_replace('DummyDate', $time, $stub);

        $stub = str_replace('DummyTime', $date, $stub);

        $stub = str_replace('DummyAuthor', env('AUTHOR', ''), $stub);

        return $stub;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Class Name.'],
        ];
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if (!$this->isSubFolder) {
            return $rootNamespace . $this->nameSpace;
        } else {
            return $rootNamespace . $this->nameSpace . "\\" . Str::pluralStudly(class_basename($this->inputName));
        }
    }

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ .'/../stubs/' . $this->stubFile . '.stub';
    }
}
