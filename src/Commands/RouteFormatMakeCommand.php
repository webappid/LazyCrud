<?php


namespace WebAppId\LazyCrud\Commands;

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
            if (isset($value['action']['middleware'])) {
                $defaultRoute = $value['action']['middleware'][0];
            } else {
                $defaultRoute = "web";
            }

            if ($routeName == $defaultRoute && (!isset($value['action']['domain']) || $value['action']['domain']=="")) {
                $name = isset($value['action']["as"]) ? $value['action']["as"] : null;
                $names = explode('.', $name);
                if ($name != null && $names[0] == 'lazy') {
                    $auth = isset($value['action']['middleware'][1]) ? $value['action']['middleware'][1] : 'none';
                    $routeList[$auth][$names[1]][$number]['uri'] = $value['uri'];
                    $routeList[$auth][$names[1]][$number]['method'] = $value['methods'][0];
                    $routeList[$auth][$names[1]][$number]['prefix'] = $value['action']["prefix"];
                    $routeList[$auth][$names[1]][$number]['name'] = $names[2];
                    $routeList[$auth][$names[1]][$number]['controller'] = isset($value['action']["controller"]) ? $value['action']["controller"] : null;
                    $number++;
                }
            }
        }
        $route = "<?php
/**
* Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
*/
";
        $route .= "Route::name('lazy.')->group(function(){";
        foreach ($routeList as $index => $data) {

            foreach ($data as $key => $item) {
                $route .= "
    Route::name('" . $key . ".')->prefix('" . $key . "')->" . (($index == "none") ? "" : "middleware('auth')->") . "group(function(){";
                foreach ($item as $detail => $value) {
                    $route .= "
        Route::" . mb_strtolower($value['method']) . "('" . str_replace($key, '', $value['uri']) . "', " . str_replace('App\Http\Controllers', '', $value['controller']) . "::class)->name('" . $value['name'] . "');";
                }
                $route .= "
    });";
            }
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
