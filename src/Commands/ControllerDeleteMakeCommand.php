<?php


namespace WebAppId\LazyCrud\Commands;

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
        $this->signature = 'lazy:controllerdelete {name} {--inject-route=} {{--auth}}';
        $this->description = 'Create a new Delete Controller Class';
        $this->stubFile = 'ControllerDelete';
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
Route::get(\'/' . str_replace('_', '-', $this->folderName) . '/{id}/delete\', \\' . $this->folder . '\\' . $this->inputName . 'DeleteController::class)->name(\'lazy.' . str_replace('_','-', $this->folderName) . '.delete\');
';
    }
}
