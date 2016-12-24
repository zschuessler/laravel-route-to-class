<?php

namespace Zschuessler\RouteToClass\Generators;

/**
 * Class GeneratorAbstract
 *
 * An abstract class for creating custom route2class generators.
 *
 * Simply extend this class and create the `generateClassName` method, which has the sole job
 * of returning a string.
 *
 * @author Zachary Schuessler <zlschuessler@gmail.com>
 * @package Zschuessler\RouteToClass\Generators
 * @see https://github.com/zschuessler/laravel-route-to-class
 */
abstract class GeneratorAbstract
{

    /**
     * Priority
     *
     * Assign priority to the generator. Defaults to 100, lower
     * numbers are treated as higher priority when loading.
     *
     * @var int
     */
    public $priority = 100;

    /**
     * Route
     *
     * The Illuminate route instance.
     *
     * @var \Illuminate\Routing\Route
     */
    public $route;

    /**
     * Generate Class Name
     *
     * Extend this function to provide logic for generating a class string.
     *
     * @return string
     */
    abstract public function generateClassName();

    /**
     * @param $route
     *
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return \Illuminate\Routing\Route
     */
    public function getRoute()
    {
        return $this->route;
    }
}
