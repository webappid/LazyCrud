<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;

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
        $this->name = 'make:lazycrud';
        $this->description = 'Create a new CRUD';
        $this->stubFile = 'TestIntegration';
        $this->nameSpace = '\Feature\Integrations';
        parent::__construct($files);
    }

    private function createControllerData()
    {
        $this->call('make:controllerindex',
            [
                "name" => $this->inputName
            ]);
    }

    private function createControllerDetail()
    {
        $this->call('make:controllerdetail',
            [
                "name" => $this->inputName
            ]);
    }

    private function createControllerDelete()
    {
        $this->call('make:controllerdelete',
            [
                "name" => $this->inputName
            ]);
    }

    private function createControllerStore()
    {
        $this->call('make:controllerstore',
            [
                "name" => $this->inputName
            ]);
    }

    private function createControllerUpdate()
    {
        $this->call('make:controllerupdate',
            [
                "name" => $this->inputName
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
        // TODO: Implement closeHandle() method.
    }
}
