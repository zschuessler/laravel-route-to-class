<?php
return [
    /**
     * Generators
     *
     * An array of classes to use in generating body classes.
     *
     * Simply add a class which implements the following abstract class:
     * \Zschuessler\RouteToClass\Generators\GeneratorAbstract
     *
     * You can put this class anywhere you wish. As an example:
     * app/Providers/RouteToClass/Generators/MyCustomGeneratorName.php
     *
     * You may also interact with the RouteToClass provider directly instead, bypassing
     * the requirement for adding generators here. Note that doing this may lead to technical debt,
     * and that adding generators is the preferred method for long-term maintenance goals.
     *
     * Example of ad-hoc solution:
     *
     * ```
     * $routeToClassProvider = app()['route2class'];
     * $routeToClassProvider->addClass('test-class');
     * $routeToClassProvider->addClass(function() {
     *     // Your own custom logic
     *     return 'test-class-custom-logic';
     * });
     * ```
     */
    'generators' => [
        /**
         * Full Route Path
         *
         * The full route is converted to a class string.
         *
         * eg:
         * `/admin/product/12/edit` becomes `admin-product-edit`
         */
        \Zschuessler\RouteToClass\Generators\FullRoutePath::class
    ]
];
