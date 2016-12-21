<?php

namespace Zschuessler\RouteToClass;

/**
 * Class RouteToClass
 *
 * Generates a Laravel View Composer variable, `$route_body_classes`, which is comprised of body classes
 * generated from any number of custom generators.
 *
 * @author Zachary Schuessler <zlschuessler@gmail.com
 * @package Zschuessler\RouteToClass
 * @see https://github.com/zschuessler/laravel-route-to-class
 */
class RouteToClass
{
    /**
     * Classes
     *
     * The authoritative array of body classes to render.
     *
     * @var \Illuminate\Support\Collection
     */
    private $classes;

    /**
     * Route
     *
     * The Illuminate route. Used to build body class names.
     *
     * @var \Illuminate\Routing\Route
     */
    private $route;

    /**
     * Generators
     *
     * A collection of class names to load as rules, for generating
     * body classes.
     *
     * @var \Illuminate\Support\Collection
     */
    private $generators;

    public function __construct()
    {
        $this->classes    = collect([]);
        $this->route      = request()->route();
        $this->generators = collect(config('routetoclass.generators'));
    }

    /**
     * Get Classes
     *
     * Gets internal classes property.
     *
     * @return \Illuminate\Support\CollectionG
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Add Class
     *
     * Allows setting a class ad-hoc, without a generator.
     *
     * Example:
     *
     * ```
     * app()['route2class']->addClass('my-class-name');
     * ```
     *
     * @param $value string The class name.
     * @param bool $sanitizeClassString Whether to clean the class name input. (eg remove special chars)
     *
     * @return $this
     */
    public function addClass($value, $sanitizeClassString = true)
    {
        $class = '';

        // Value is a string
        if (true === is_string($value)) {
            $class = $value;
        }

        // Value is a callable function
        if (true === is_callable($value)) {
            $callableResult = $value();

            if (!is_string($callableResult)) {
                throw new Exception(
                    'User called function did not return a string for method addClass.'
                );
            }

            $class = $callableResult;
        }

        // Sanitize string unless override parameter set
        if (true === $sanitizeClassString) {
            $class = $this->sanitizeClassString($value);
        }

        // Add class to stack
        $this->classes->push($class);

        return $this;
    }

    /**
     * Sanitize CSS Class String
     *
     * Currently a simple wrapper around the `str_slug` method in Laravel.
     *
     * @param $value string The class name to sanitize.
     *
     * @return string A sanitized class name (a valid css class, eg no special characters).
     */
    public function sanitizeClassString($value)
    {
        return str_slug($value);
    }

    /**
     * Generate Class String
     *
     * Generates the full body class string.
     *
     * @return mixed
     */
    public function generateClassString()
    {
        // Load all generators, sorted by priority
        $generators = config('route2class')['generators'];

        $generators = collect($generators)
            ->map(function($generatorClassName) {
                $generator = new $generatorClassName;
                $generator->setRoute($this->route);

                return $generator;
            })
            ->sortBy('priority');

        // Run all generators
        $classes = $generators->map(function($generatorClass) {
            return [get_class() => $generatorClass->generateClassName()];
        });

        // Allow any prior-set classes to take precedence over generators.
        $classes = $classes->merge($this->classes->all());

        $classString = $classes
            ->map(function($className) {
                if (is_array($className)) {
                    return array_values($className)[0];
                } else if (is_string($className)) {
                    return $className;
                }
            })
            ->implode(' ');

        return $classString;
    }
}