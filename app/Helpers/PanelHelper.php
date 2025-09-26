<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class PanelHelper
{
    public static function get_routes()
    {
        //$panelName = config('services.env_setting.panel');
        $panels = ['/admin-panel', 'admin-panel', 'admin-panel/mailing-list'];
        $routes = [];


        //dd(Route::getRoutes());
        foreach (Route::getRoutes() as $route)
        {
            // if($route->getPrefix() == $panelName){
            if (in_array($route->getPrefix(), $panels)){                
                $routes[] = $route;
            }
            // if (self::is_panel_routes($route, $panelName)) {
            //     dd($panelName);
            //     $routes[] = $route;
            // }
        }

        return $routes;
    }

    /**
     * @param $route
     * @param $panelName
     * @return bool
     */
    private static function is_panel_routes($route, $panelName): bool
    {
        return strpos($route->getPrefix(), $panelName) !== false && in_array('admin', $route->middleware());
    }
}
