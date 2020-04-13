<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 15.29
 * Class TestServiceMakeCommand
 * @package WebAppId\LazyCrud
 */
class TestServiceMakeCommand extends TestSmartMakeCommand
{
    use ColumnList;

    /**
     * ControllerStoreMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'ServiceTest';
        $this->name = 'make:testservice';
        $this->description = 'Create a new Test Service Class';
        $this->stubFile = 'TestService';
        $this->nameSpace = '\Feature\Services';
        $this->hidden = true;
        parent::__construct($files);
    }

    private function createServiceContract()
    {
        $this->call('make:testsmarttestcase',
            [
                "name" => 'Smart'
            ]);
    }

    function replaceClassCustom(string $stub)
    {
        return $stub;
    }

    function prevHandle()
    {
        $this->createServiceContract();
    }

    function closeHandle()
    {
        // TODO: Implement closeHandle() method.
    }
}
