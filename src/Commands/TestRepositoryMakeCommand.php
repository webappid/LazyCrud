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
class TestRepositoryMakeCommand extends TestSmartMakeCommand
{
    use ColumnList;

    /**
     * ControllerStoreMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'RepositoryTest';
        $this->name = 'lazy:testrepository';
        $this->description = 'Create a new Test Repository Class';
        $this->stubFile = 'TestRepository';
        $this->nameSpace = '\Unit\Repositories';
        $this->hidden = true;
        parent::__construct($files);
    }

    private function createServiceContract()
    {
        $this->call('lazy:testsmarttestcase',
            [
                "name" => 'Smart'
            ]);
    }

    function replaceClassCustom(string $stub)
    {
        $this->propertiesModel = 3;
        $property = $this->getColumnProperties($this->inputName);
        return str_replace('DummyData', $property, $stub);
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
