<?php

namespace Lar\Roads;

use Illuminate\Support\ServiceProvider as ServiceProviderIlluminate;
use Lar\Roads\Commands\MakeRoadCommand;

/**
 * Class ServiceProvider
 *
 * @package Lar\Layout
 */
class ServiceProvider extends ServiceProviderIlluminate
{
    /**
     * @var array
     */
    protected $commands = [
        MakeRoadCommand::class
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'encrypt_cookies' => \App\Http\Middleware\EncryptCookies::class,
        'queued_cookies' => \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        'start_session' => \Illuminate\Session\Middleware\StartSession::class,
        'share_errors' => \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        'verify_csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
        'substitute_bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Exception
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/roads.php' => config_path('roads.php'),
        ], 'ljs-roads-config');

        foreach (config('roads') as $file => $attributes) {

            $file = base_path($file . ".php");

            if (is_file($file)) {

                if (!$attributes) {

                    $attributes = [];
                }

                if (!is_array($attributes) && is_string($attributes) && !empty($attributes)) {

                    $attributes = ['middleware' => $attributes];
                }

                if (!isset($attributes['namespace'])) { $attributes['namespace'] = 'App\Http\Controllers'; }

                \Road::middleware([])->__tmpAttribute(is_array($attributes) ? $attributes : [])->group($file);
            }
        }

        $routes = \Route::getRoutes()->getRoutesByMethod();

        $created_routes = [];

        if (isset($routes["GET"])) {

            $repl_lang = \App::getLocale();

            /** @var \Illuminate\Routing\Route $route */
            foreach ($routes["GET"] as $route) {

                if (config('layout.lang_mode') && isset($route->action['middleware']) && in_array('lang', $route->action['middleware']) && in_array('GET', $route->methods)) {

                    $pref = preg_replace("/^{$repl_lang}\//", '', $route->uri);

                    $pref = empty($pref) ? '/' : $pref;

                    if (!isset($created_routes[$pref])) {

                        $created_routes[$pref] = $pref;

                        \Route::middleware(['web'])->get($pref, 'Lar\\Roads\\RedirectController@index');
                    }

                }
            }
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/roads.php', 'roads'
        );
        $this->registerRouteMiddleware();
        $this->commands($this->commands);
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
    }
}

