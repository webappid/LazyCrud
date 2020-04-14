<?php


namespace WebAppId\LazyCrud;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 15/04/20
 * Time: 03.25
 * Class RouteFormatMakeCommand
 * @package WebAppId\LazyCrud
 */
class RouteFormatMakeCommand extends Command
{
    public function __construct()
    {
        $this->signature = 'lazy:format {{--route=}';
        $this->description = 'Reformat Lazy Route';
        parent::__construct();
    }

    private function reformatRoute()
    {
        if ($this->option('route') == null) {
            $routeName = 'web';
        } else {
            $routeName = $this->option('route');
        }
        $routeFile = 'lazy-' . $routeName . '.php';
        $routeList = [];
        $number = 0;
        $routeCollection = json_decode(json_encode(Route::getRoutes()->get(), true), true);

        foreach ($routeCollection as $key => $value) {
            if ($routeName == $value['action']['middleware'][0]) {
                $name = isset($value['action']["as"]) ? $value['action']["as"] : null;
                $names = explode('.', $name);
                if ($name != null && $names[0] == 'lazy') {
                    $routeList[$names[1]][$number]['uri'] = $value['uri'];
                    $routeList[$names[1]][$number]['method'] = $value['methods'][0];
                    $routeList[$names[1]][$number]['auth'] = isset($value['action']['middleware'][1]) ? $value['action']['middleware'][1] : null;
                    $routeList[$names[1]][$number]['prefix'] = $value['action']["prefix"];
                    $routeList[$names[1]][$number]['name'] = $names[2];
                    $routeList[$names[1]][$number]['controller'] = isset($value['action']["controller"]) ? $value['action']["controller"] : null;
                    $number++;
                }
            }
        }
        $route = "<?php
/**
* Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
*/
Route::name('lazy.')->group(function(){";
        foreach ($routeList as $key => $item) {
            $route .= "
    Route::name('" . $key . ".')->prefix('" . $key . "')->group(function(){";
            foreach ($item as $detail => $value) {
                $route .= "
        Route::" . mb_strtolower($value['method']) . "('" . str_replace($key, '', $value['uri']) . "', " . str_replace('App\Http\Controllers', '', $value['controller']) . "::class)->name('" . $value['name'] . "');";
            }
            $route .= "
    });";
        }
        $route .= "
});";
        $fileSystem = new Filesystem();
        $fileSystem->put('routes/' . $routeFile, $route);
    }

    function handle()
    {
        $this->reformatRoute();
    }
}
