<?php
return [
    /**
     * Generators
     *
     * An array of classes to use in generating body classes.
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