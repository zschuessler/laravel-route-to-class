<?php

namespace Zschuessler;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class RouteBodyClass extends ServiceProvider
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

