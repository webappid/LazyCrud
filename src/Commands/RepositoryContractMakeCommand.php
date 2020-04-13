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
class RepositoryContractMakeCommand extends SmartMakeCommand
{
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'RepositoryContract';
        $this->name = 'make:repositorycontract';
        $this->description = 'Create a new repository contract interface';
        $this->stubFile = 'RepositoryContract';
        $this->nameSpace = '\Repositories\Contracts';
        $this->hidden = true;
        parent::__construct($files);
    }

    private function createRepositoryRequest()
    {
        $this->call('make:repositoryrequest',
            [
                "name" => $this->inputName
            ]);
    }

    private function createModel()
    {
        $this->call('make:smartmodel',
            [
                "name" => $this->inputName
            ]);
    }

    function prevHandle()
    {
        $this->createModel();
        $this->createRepositoryRequest();
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
