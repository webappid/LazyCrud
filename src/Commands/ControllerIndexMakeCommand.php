<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 03.55
 * Class ControllerDetailMakeCommand
 * @package WebAppId\LazyCrud
 */
class ControllerIndexMakeCommand extends ControllerMakeCommand
{
    /**
     * ControllerStoreMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'IndexController';
        $this->signature = 'make:lazycontrollerindex {name} {--inject-route=}';
        $this->description = 'Create a new Index Controller Class';
        $this->stubFile = 'ControllerIndex';
        $this->nameSpace = '\Http\Controllers';
        $this->isSubFolder = true;
        $this->hidden = true;
        parent::__construct($files);
    }

    private function createServiceContract()
    {
        $this->call('make:lazyservice',
            [
                "name" => $this->inputName
            ]);
    }

    function prevHandle()
    {
        if($this->hasOption('inject-route')){
            $this->injectRoute = $this->option('inject-route');
        }
        $this->createServiceContract();
    }

    function replaceClassCustom(string $stub)
    {
        return $stub;
    }

    function injectRouter(): string
    {
        return '
Route::get(\'/'.$this->lower.'\', \\'.$this->folder.'\\'.$this->inputName.'IndexController::class)->name(\'lazy.'.$this->lower.'.index\');
';
    }
}
