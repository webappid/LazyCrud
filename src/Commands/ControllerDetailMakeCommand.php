<?php


namespace WebAppId\LazyCrud\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 03.55
 * Class ControllerDetailMakeCommand
 * @package WebAppId\LazyCrud
 */
class ControllerDetailMakeCommand extends ControllerMakeCommand
{
    /**
     * ControllerStoreMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'DetailController';
        $this->signature = 'lazy:controllerdetail {name} {--inject-route=} {{--auth}}';
        $this->description = 'Create a new Detail Controller Class';
        $this->stubFile = 'ControllerDetail';
        $this->nameSpace = '\Http\Controllers';
        $this->isSubFolder = true;
        $this->hidden = true;
        parent::__construct($files);
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
        $this->createServiceContract();
    }

    function replaceClassCustom(string $stub)
    {
        return $stub;
    }

    function injectRouter(): string
    {
        return '
Route::get(\'/' . str_replace('_', '-', $this->folderName) . '/{id}\', \\' . $this->folder . '\\' . $this->inputName . 'DetailController::class)->name(\'lazy.' . str_replace('_', '-', $this->folderName) . '.detail\');
';
    }
}
