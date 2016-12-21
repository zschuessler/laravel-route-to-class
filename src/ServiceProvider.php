<?php

namespace Zschuessler\RouteToClass;

use Illuminate\Support\Facades\View;
use Zschuessler\RouteToClass\RouteToClass;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        // Config
        $this->publishes([
            __DIR__.'/config/route2class.php' => config_path('route2class.php')
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/config/route2class.php', 'route2class'
        );

        // Create app singleton
        $this->app->singleton('route2class', function () {
            return new RouteToClass();
        });
    }
    /**
     * Bootstrap the application services.
     *
     * Creates a view composer for all views.
     * Assigns the variable `route_body_classes` to each view.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Register View composer
         *
         * Share global view variable `$route_body_classes`, which represents the final body class
         * string after all generators have run.
         */
        View::composer('*', function (\Illuminate\View\View $view) {

            // Let app handle 404s
            if (!request()->route()) {
                return;
            }

            $routeToClass = app()['route2class'];

            View::share('route_body_classes', $routeToClass->generateClassString());
        });
    }
}

