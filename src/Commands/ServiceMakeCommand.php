<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 02.13
 * Class ServiceMakeCommand
 * @package WebAppId\LazyCrud
 */
class ServiceMakeCommand extends SmartMakeCommand
{
    /**
     * RepositoryMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'Service';
        $this->name = 'make:lazyservice';
        $this->description = 'Create a new Service Class';
        $this->stubFile = 'Service';
        $this->nameSpace = '\Services';
        $this->hidden = true;
        parent::__construct($files);
    }

    private function createServiceContract()
    {
        $this->call('make:lazyservicecontract',
            [
                "name" => $this->inputName
            ]);
    }

    private function createTestService()
    {
        $this->call('make:lazytestservice',
            [
                "name" => $this->inputName
            ]);
    }


    function prevHandle()
    {
        $this->createServiceContract();
        $this->createTestService();
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
