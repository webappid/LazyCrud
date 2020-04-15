<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 15.38
 * Class TestSmartTestCaseMakeCommand
 * @package WebAppId\LazyCrud
 */
class TestSmartTestCaseMakeCommand extends TestSmartMakeCommand
{
    use ColumnList;

    /**
     * ControllerStoreMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'TestCase';
        $this->name = 'lazy:testsmarttestcase';
        $this->description = 'Create a new Smart Test CaseClass';
        $this->stubFile = 'SmartTestCase';
        $this->nameSpace = '\\';
        $this->hidden = true;
        parent::__construct($files);
    }

    function replaceClassCustom(string $stub)
    {
        return str_replace('AuthClass', Config::get('auth.providers.users.model'), $stub);
    }

    function prevHandle()
    {
        // TODO: Implement prevHandle() method.
    }

    function closeHandle()
    {
        // TODO: Implement closeHandle() method.
    }
}
