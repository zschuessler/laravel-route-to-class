<?php

namespace Zschuessler\RouteToClass;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'route2class';
    }
}
