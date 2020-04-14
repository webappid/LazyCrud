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
     * @var array
     */
    private $options = [];

    /**
     * ModelMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'IntegrationTest';
        $this->signature = 'lazy:crud {name} {--inject-route=} {{--auth}}';
        $this->description = 'Create a new CRUD';
        $this->stubFile = 'TestIntegration';
        $this->nameSpace = '\Feature\Integrations';
        parent::__construct($files);
    }

    private function createControllerData()
    {
        $this->call('make:lazycontrollerindex',
            $this->options);
    }

    private function createControllerDetail()
    {
        $this->call('make:lazycontrollerdetail',
            $this->options);
    }

    private function createControllerDelete()
    {
        $this->call('make:lazycontrollerdelete',
            $this->options);
    }

    private function createControllerStore()
    {
        $this->call('make:lazycontrollerstore',
            $this->options);
    }

    private function createControllerUpdate()
    {
        $this->call('make:lazycontrollerupdate',
            $this->options);
    }

    function prevHandle()
    {
        $this->options = [
            "name" => $this->inputName,
            "--inject-route" => $this->option('inject-route')
        ];

        if ($this->option('auth')) {
            $this->options["--auth"] = $this->option('auth');
        }
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
            report($e);
        }
        $includeFile = 'require "lazy-' . $route . '.php";';
        if (!strpos($routeData, $includeFile)) {
            $routeData .= '
require "lazy-web.php";';
            $fileSystem->put('routes/' . $route . '.php', $routeData);
        }
    }
}
