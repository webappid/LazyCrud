<?php


namespace WebAppId\LazyCrud\Commands;

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

    use ColumnList;

    /**
     * RepositoryMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'Repositories';
        $this->name = 'lazy:repository';
        $this->description = 'Create a new Repositories Class';
        $this->stubFile = 'Repositories';
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
        $this->propertiesModel = 4;
        $column = $this->getColumnProperties($this->inputName);
        if ($this->secondColumn != null) {
            $stub = str_replace('searchColumn', $this->secondColumn, $stub);
        }
        return str_replace('ColumnList', $column, $stub);
    }

    function closeHandle()
    {
        // TODO: Implement closeHandle() method.
    }
}
