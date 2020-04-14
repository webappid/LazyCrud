<?php


namespace WebAppId\LazyCrud;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
     * @var string
     */
    protected $injectRoute = null;

    /**
     * @var bool
     */
    protected $auth = false;

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
        if ($this->injectRoute != null) {
            $route = $this->injectRoute;
        } else {
            $route = Config::get('lazycrud.inject.route');
        }
        $this->lower = strtolower($this->inputName);

        $this->folder = Str::pluralStudly(class_basename($this->inputName));

        try {
            $routeFile = $this->files->get(base_path('routes/lazy-' . $route));
        } catch (FileNotFoundException $e) {
            $routeFile = "<?php
/**
* Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
*/

";
        }
        if ($this->auth) {
            $middleware = str_replace('.php', '', $route);
            $routeFile .= "
Route::middleware('auth" . ($middleware == 'api' ? ':api' : '') . "')->group(function(){";
        }
        $routeFile .= $this->injectRouter();
        if ($this->auth) {
            $routeFile .= "
});";
        }

        $this->files->put('routes/lazy-' . $route, $routeFile);
    }
}
