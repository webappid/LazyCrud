<?php


namespace WebAppId\LazyCrud\Commands;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 16/04/20
 * Time: 09.44
 * Class RequestSearchMakeCommand
 * @package WebAppId\LazyCrud
 */
class RequestSearchMakeCommand extends SmartMakeCommand
{
    use ColumnList;

    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'SearchRequest';
        $this->name = 'lazy:searchrequest';
        $this->description = 'Create a new Search Request Class';
        $this->stubFile = 'SearchRequest';
        $this->nameSpace = '\Requests';
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
