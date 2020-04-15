<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 11/04/20
 * Time: 20.13
 * Class ModelMakeCommand
 * @package WebAppId\LazyCrud
 */
class ModelMakeCommand extends SmartMakeCommand
{
    /**
     * ModelMakeCommand constructor.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = '';
        $this->name = 'lazy:model';
        $this->description = 'Create a new Model Class';
        $this->stubFile = 'Model';
        $this->nameSpace = '\Models';
        $this->hidden = true;
        parent::__construct($files);
    }

    function replaceClassCustom(string $stub)
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->inputName)));

        $result = Schema::connection(null)->getColumnListing($table);

        $properties = [];
        foreach ($result as $columnName) {
            $properties[] = "'" . $columnName . "'";
        }
        $property = "[" . implode(', ', $properties) . "]";

        $stub = str_replace('DummyFillable', $property, $stub);

        $stub = str_replace('DummyAuthor', env('AUTHOR', ''), $stub);

        return str_replace('DummyTable', $table, $stub);
    }

    function prevHandle()
    {
        //nothing to execute
    }

    function closeHandle()
    {
        // TODO: Implement closeHandle() method.
    }
}
