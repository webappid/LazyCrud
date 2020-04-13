<?php


namespace WebAppId\LazyCrud;


use Illuminate\Support\Str;

abstract class TestSmartMakeCommand extends SmartMakeCommand
{
    protected function rootNamespace()
    {
        return 'Tests';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path.base'] . '/tests/' . str_replace('\\', '/', $name) . '.php';
    }
}
