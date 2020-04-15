<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 11/04/20
 * Time: 19.06
 * Class RepositoryMakeCommand
 * @package WebAppId\LazyCrud
 */
class RepositoryMakeCommand extends SmartMakeCommand
{
    /**
     * RepositoryMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'Repository';
        $this->name = 'lazy:repository';
        $this->description = 'Create a new Repository Class';
        $this->stubFile = 'Repository';
        $this->nameSpace = '\Repositories';
        parent::__construct($files);
    }

    private function createRepositoryContract()
    {
        $this->call('lazy:repositorycontract',
            [
                "name" => $this->inputName
            ]);
    }

    private function createRepositoryTest()
    {
        $this->call('lazy:testrepository',
            [
                "name" => $this->inputName
            ]);
    }

    function prevHandle()
    {
        $this->createRepositoryContract();
        $this->createRepositoryTest();
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
