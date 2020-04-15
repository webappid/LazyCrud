<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 11/04/20
 * Time: 19.06
 * Class RequestMakeCommand
 * @package WebAppId\LazyCrud
 */
class RequestMakeCommand extends SmartMakeCommand
{
    use ColumnList;

    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'Request';
        $this->name = 'lazy:request';
        $this->description = 'Create a new Request Class';
        $this->stubFile = 'Request';
        $this->nameSpace = '\Requests';
        $this->hidden = true;
        parent::__construct($files);
    }

    function replaceClassCustom(string $stub)
    {
        $this->propertiesModel = 1;
        $property = $this->getColumnProperties($this->inputName);

        return str_replace('RulesData', $property, $stub);
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
