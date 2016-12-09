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
        View::composer('*', function (\Illuminate\View\View $view) {
            $route = request()->route()->getPath();

            // Remove route parameters. product_id is removed here: `controller/product/{product_id}`
            $clean = preg_replace("/\{([^}]+)\}/", '', $route);

            // Remove characters that aren't alpha, numeric, or dashes
            $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);

            // Remove any double dashes from replace functions. eg: `product--name` should be `product-name`
            $clean = strtolower(trim($clean, '-'));

            // Replace special characters with a dash
            $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

            View::share('route_body_classes', $clean);

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

