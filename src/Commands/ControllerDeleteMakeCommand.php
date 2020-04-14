<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 04.13
 * Class ControllerDeleteMakeCommand
 * @package WebAppId\LazyCrud
 */
class ControllerDeleteMakeCommand extends ControllerMakeCommand
{
    /**
     * ControllerStoreMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'DeleteController';
        $this->signature = 'make:lazycontrollerdelete {name} {--inject-route=}';
        $this->description = 'Create a new Delete Controller Class';
        $this->stubFile = 'ControllerDelete';
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
Route::get(\'/'.$this->lower.'/{id}/delete\', \\'.$this->folder.'\\'.$this->inputName.'DeleteController::class)->name(\'lazy.'.$this->lower.'.delete\');
';
    }
}
