<?php


namespace WebAppId\LazyCrud\Commands;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 03.55
 * Class ControllerDetailMakeCommand
 * @package WebAppId\LazyCrud
 */
class ControllerListMakeCommand extends ControllerMakeCommand
{
    /**
     * ControllerStoreMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'ListController';
        $this->signature = 'lazy:controllerlist {name} {--inject-route=} {{--auth}}';
        $this->description = 'Create a new List Controller Class';
        $this->stubFile = 'ControllerList';
        $this->nameSpace = '\Http\Controllers';
        $this->isSubFolder = true;
        $this->hidden = true;
        parent::__construct($files);
    }

    private function createService()
    {
        $this->call('lazy:service',
            [
                "name" => $this->inputName
            ]);
    }

    private function createSearchRequest()
    {
        $this->call('lazy:searchrequest',
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
        $this->createService();

        $this->createSearchRequest();
    }

    function replaceClassCustom(string $stub)
    {
        return $stub;
    }

    function injectRouter(): string
    {
        return '
Route::get(\'/' . str_replace('_', '-', $this->folderName) . '/list\', \\' . $this->folder . '\\' . $this->inputName . 'ListController::class)->name(\'lazy.' . str_replace('_', '-', $this->folderName) . '.list\');
';
    }
}
