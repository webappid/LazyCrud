<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 11/04/20
 * Time: 19.06
 * Class ServiceRequestMakeCommand
 * @package WebAppId\LazyCrud
 */
class ServiceRequestMakeCommand extends SmartMakeCommand
{
    use ColumnList;

    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'ServiceRequest';
        $this->name = 'make:servicerequest';
        $this->description = 'Create a new Service Request Class';
        $this->stubFile = 'ServiceRequest';
        $this->nameSpace = '\Services\Requests';
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
