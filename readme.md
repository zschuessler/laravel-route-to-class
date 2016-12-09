# Laravel Routes to Body Class

Quickly add a unique body class to your view based on your Laravel routes.

## Example

You have a route defined for editing your products:

```php
Route::get(
    '/admin/products/{product_id}/edit',
    'ProductAdminController@getEdit'
)
```

This library will add the class `admin-products-edit` as a shared view variable automatically.

## Install

### 1 - Require Package

```
composer require zschuessler/laravel-route-to-class
```

### 2 - Add Service Provider to Your App

Add the following line under the `providers` array key in *app/config.php*:

```php
/**
 * Custom Service Providers
 */
Zschuessler\RouteToClass\ServiceProvider::class,
```

### 3 - Use It

You now have access to a shared view variable `$route_body_class`. Use it in any of your views like so:

```php
<body class="{{ $route_body_classes }}">
```

## License

This is public domain. Do what you want.