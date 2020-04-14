<?php


namespace WebAppId\LazyCrud;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 03.55
 * Class ControllerDetailMakeCommand
 * @package WebAppId\LazyCrud
 */
class CrudMakeCommand extends TestSmartMakeCommand
{
    /**
     * ModelMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'IntegrationTest';
        $this->signature = 'lazy:crud {name} {--inject-route=}';
        $this->description = 'Create a new CRUD';
        $this->stubFile = 'TestIntegration';
        $this->nameSpace = '\Feature\Integrations';
        parent::__construct($files);
    }

    private function createControllerData()
    {
        $this->call('make:lazycontrollerindex',
            [
                "name" => $this->inputName,
                "--inject-route" => $this->option('inject-route')
            ]);
    }

    private function createControllerDetail()
    {
        $this->call('make:lazycontrollerdetail',
            [
                "name" => $this->inputName,
                "--inject-route" => $this->option('inject-route')
            ]);
    }

    private function createControllerDelete()
    {
        $this->call('make:lazycontrollerdelete',
            [
                "name" => $this->inputName,
                "--inject-route" => $this->option('inject-route')
            ]);
    }

    private function createControllerStore()
    {
        $this->call('make:lazycontrollerstore',
            [
                "name" => $this->inputName,
                "--inject-route" => $this->option('inject-route')
            ]);
    }

    private function createControllerUpdate()
    {
        $this->call('make:lazycontrollerupdate',
            [
                "name" => $this->inputName,
                "--inject-route" => $this->option('inject-route')
            ]);
    }

    function prevHandle()
    {
        $this->createControllerData();
        $this->createControllerDetail();
        $this->createControllerDelete();
        $this->createControllerStore();
        $this->createControllerUpdate();

    }

    function replaceClassCustom(string $stub)
    {
        return $stub;
    }

    function closeHandle()
    {
        if ($this->option('inject-route') != null) {
            $route = $this->option('inject-route');
        } else {
            $route = 'web';
        }
        $fileSystem = new Filesystem();
        try {
            $routeData = $fileSystem->get('routes/' . $route . '.php');
        } catch (FileNotFoundException $e) {
        }
        $includeFile = 'require_once "lazy-' . $route . '.php";';
        if (!strpos($routeData, $includeFile)) {
            $routeData .= '
require "lazy-web.php";';
            $fileSystem->put('routes/' . $route . '.php', $routeData);
        }
    }
}
