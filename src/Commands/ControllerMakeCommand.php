<?php


namespace WebAppId\LazyCrud;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

abstract class ControllerMakeCommand extends SmartMakeCommand
{
    abstract function injectRouter(): string;

    /**
     * @var string
     */
    protected $lower;

    /**
     * @var string
     */
    protected $folder;

    /**
     * @var array
     */
    protected $injectList;

    public function __construct(Filesystem $files)
    {
        $this->injectList = Config::get('lazycrud.inject.controller');
        parent::__construct($files);
    }

    function closeHandle()
    {
        $this->lower = strtolower($this->inputName);

        $this->folder = Str::pluralStudly(class_basename($this->inputName));

        $route = Config::get('lazycrud.inject.route');

        $routeFile = $this->files->get(base_path('routes/' . $route));

        $routeFile .= $this->injectRouter();

        $this->files->put('routes/' . $route, $routeFile);
    }
}
