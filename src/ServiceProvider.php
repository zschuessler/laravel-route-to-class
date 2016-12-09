<?php

namespace Zschuessler\RouteToClass;

use Illuminate\Support\Facades\View;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {

        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

