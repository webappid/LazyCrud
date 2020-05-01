<?php


namespace WebAppId\LazyCrud\Commands;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 11/04/20
 * Time: 19.06
 * Class RepositoryRequestMakeCommand
 * @package WebAppId\LazyCrud
 */
class RepositoryRequestMakeCommand extends SmartMakeCommand
{
    use ColumnList;

    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'RepositoryRequest';
        $this->name = 'lazy:repositoryrequest';
        $this->description = 'Create a new Repository Request Class';
        $this->stubFile = 'RepositoryRequest';
        $this->nameSpace = '\Repositories\Requests';
        $this->hidden = true;
        parent::__construct($files);
    }

    function replaceClassCustom(string $stub)
    {
        $this->propertiesModel = 2;
        $property = $this->getColumnProperties($this->inputName);

        return str_replace('DummyProperty', $property, $stub);
    }

    function prevHandle()
    {
        //noting to execute
    }

    function closeHandle()
    {
        // TODO: Implement closeHandle() method.
    }
}
