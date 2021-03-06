<?php


namespace WebAppId\LazyCrud\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 03.34
 * Class ControllerStoreMakeCommand
 * @package WebAppId\LazyCrud
 */
class ControllerStoreMakeCommand extends ControllerMakeCommand
{
    /**
     * ControllerStoreMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'StoreController';
        $this->signature = 'lazy:controllerstore {name} {--inject-route=} {{--auth}}';
        $this->description = 'Create a new Store Controller Class';
        $this->stubFile = 'ControllerStore';
        $this->nameSpace = '\Http\Controllers';
        $this->isSubFolder = true;
        $this->hidden = true;
        parent::__construct($files);
    }

    private function createRequest()
    {
        $this->call('lazy:request',
            [
                "name" => $this->inputName
            ]);
    }

    private function createServiceContract()
    {
        $this->call('lazy:service',
            [
                "name" => $this->inputName
            ]);
    }


    function prevHandle()
    {
        if ($this->option('inject-route')) {
            $this->injectRoute = $this->option('inject-route');
        }

        if ($this->option('auth')) {
            $this->auth = true;
        }
        $this->createRequest();
        $this->createServiceContract();
    }

    function replaceClassCustom(string $stub)
    {
        $injectedData = '';
        $injectUse = '';
        foreach ($this->injectList as $key => $value) {
            $injectedData .= '$' . lcfirst($this->inputName) . 'ServiceRequest->' . $key . ' = ' . $value['method'] . ';
            ';
            $injectUse .= $value['use'];
        }
        $stub = str_replace('InjectUse', $injectUse, $stub);
        return str_replace('InjectData', $injectedData, $stub);
    }

    function injectRouter(): string
    {
        return '
Route::post(\'/' . str_replace('_', '-', $this->folderName) . '\', \\' . $this->folder . '\\' . $this->inputName . 'StoreController::class)->name(\'lazy.' . str_replace('_', '-', $this->folderName) . '.store\');
';
    }
}
