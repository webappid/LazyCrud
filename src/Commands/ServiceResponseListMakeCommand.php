<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 01.34
 * Class ServiceResponseListMakeCommand
 * @package WebAppId\LazyCrud
 */
class ServiceResponseListMakeCommand extends SmartMakeCommand
{

    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'ServiceResponseList';
        $this->name = 'make:serviceresponselist';
        $this->description = 'Create a new Service Response List Class';
        $this->stubFile = 'ServiceResponseList';
        $this->nameSpace = '\Services\Responses';
        $this->hidden = true;
        parent::__construct($files);
    }

    function replaceClassCustom(string $stub)
    {
        return $stub;
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
