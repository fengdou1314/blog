<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    //--start-- 2017-12-13 新增各端路由的文件
    protected $adminNamespace;
    protected $homeNamespace;
    protected $mobileNamespace;
    protected $currentDomain;
    //--end--

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //--start-- 2017-12-13 新增各端路由的文件
        $this->adminNamespace  = "App\Http\Controllers\admin";
        $this->homeNamespace   = "App\Http\Controllers\home";
        $this->mobileNamespace = "App\Http\Controllers\mobile";
        $this->currentDomain   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "";
        //--end--

        parent::boot($router);

    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $adminUrl  = config('route.admin_url');
        $mobileUrl = config('route.mobile_url');
        $homeUrl   = config('route.home_url');

        switch ($this->currentDomain)
        {
            case $adminUrl:
                // admin路由
                $router->group([
                    'domain' => $adminUrl,
                    'namespace' => $this->adminNamespace
                ], function ($router) {
                        require app_path('Http/routes_admin.php');
                    }
                );
                break;
            case $mobileUrl:
                // mobil路由
                $router->group([
                    'domain' => $mobileUrl,
                    'namespace' => $this->mobileNamespace
                ], function ($router) {
                        require app_path('Http/routes_mobile.php');
                    }
                );
                break;
            default:
                // home路由
                $router->group([
                    'domain' => $homeUrl,
                    'namespace' => $this->homeNamespace
                ], function ($router) {
                        require app_path('Http/routes_home.php');
                    }
                );
                break;
        }

        $this->mapWebRoutes($router);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $adminUrl  = config('route.admin_url');
        $mobileUrl = config('route.mobile_url');
        $homeUrl   = config('route.home_url');

        switch ($this->currentDomain)
        {
            //admin端
            case $adminUrl:
                 $router->group([
                    'namespace' => $this->namespace,
                    'middleware' => 'admin',
                 ], function ($router) {
                    require app_path('Http/routes_admin.php');
                 });
                 break;
            //mobile端
            case $mobileUrl:
                 $router->group([
                    'namespace' => $this->namespace,
                    'middleware' => 'mobile',
                 ], function ($router) {
                    require app_path('Http/routes_mobile.php');
                 });
                 break;
            //home端
            case $homeUrl:
                 $router->group([
                    'namespace' => $this->namespace,
                    'middleware' => 'home',
                 ], function ($router) {
                    require app_path('Http/routes_home.php');
                 });
                 break;
            //默认
            default:
                $router->group([
                    'namespace' => $this->namespace,
                    'middleware' => 'web',
                ], function ($router) {
                    require app_path('Http/routes.php');
                });
        }
    }
}
