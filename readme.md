![Alt text](https://raw.githubusercontent.com/zschuessler/laravel-route-to-class/master/readme-screenshot.png "Screenshot")

# Dynamic Body Classes for Laravel

Quickly add body classes to your Laravel app based on rules you set.

Example of implementations:

1. Browsing as a guest might add `user-isGuest` as a class.
2. Browsing an admin panel might add `admin-panel` as a class.
3. All user profile routes might have `user-profile` as a class.

It's easy to write your own rules! You can either write your own generator classes
or use the ad-hoc API by interacting with the library singleton directly.

## Docs

All contents of this readme are present here in more organized format:

[https://laravel-route-to-class.readme.io/docs](https://laravel-route-to-class.readme.io/docs)

## Quickstart

**Require the package in your composer setup.**

```
composer require zschuessler/laravel-route-to-class
```

> If you do run the package on Laravel 5.5+, you can start using the package at this point. [package auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518) takes care of the magic of adding the service provider.

**Add the service provider to your app configuration**

If you do not run Laravel 5.5 (or higher), then add the following line under the `providers` array key in *app/config.php*:

```php
/**
 * Custom Service Providers
 */
Zschuessler\RouteToClass\ServiceProvider::class,
```

**Publish the configuration file**

Run the following command in the root directory of your project:

```php
php artisan vendor:publish --provider="Zschuessler\RouteToClass\ServiceProvider"
```

**Use In Layout**

You can either use the included Blade directive, or access the Route2Class facade directly for
outputting your classes.

*Blade*

Two important notes for using the Blade directive:

1. The Blade directive will follow any caching solutions you have setup. This is great for production, but keep in mind
on development you may be viewing cached classes when modifying generators.
2. The Blade directive runs before all other view template code. As such, any calls to the Route2Class
package in a view will not show up in your class list. 

```php
<body class="@route2class_generate_classes"></body>
```

*Facade*

Facades are not cached in the manner Blade directives are, making them great for development
environments. And because we aren't using a Blade directive, you can modify classes and generators
within view templates too.
 
Use it in any of your views like so:

```php
<?php
// This is now possible, too:
\Route2Class::addClass('test');
?>


<body class="{{ \Route2Class::generateClassString(); }}"></body>
```

## Implement Your Own Rules

You can implement your own rules in one of two methods.

### Create Generator File

This is the preferred method since you will always know where your class modifiers (
called generators) will live.

First decide where you would like to keep your generators. For the purpose of this example
we will use the following directory:

`app/Providers/RouteToClass/UserTypeGenerator.php`

All you have to do is extend the `GeneratorAbstract.php` file and implement a method
which returns the class string. See below for a simple example:

```php
<?php

namespace App\Providers\RouteToClass;

use Zschuessler\RouteToClass\Generators\GeneratorAbstract;

class UserTypeGenerator extends GeneratorAbstract
{
    public function generateClassName()
    {
        // Use your own logic here to determine user type
        $userType = 'admin';

        return 'user-' . $userType;
    }
}
```

Next add a reference to the generator in your `/config/route2class.php` configuration:

```
App\Providers\RouteToClass\UserTypeGenerator::class,
```

Now when you call the facade or Blade directive in a view template, you will
see the class `user-admin` - **neat**!

See this file for a real-life generator example:

https://github.com/zschuessler/laravel-route-to-class/blob/master/src/Generators/FullRoutePath.php

### Ad-Hoc Class Additions

You can interact with the body classes directly by calling the `addClass` method on the
provider.
 
Here's an example using the default Laravel project's routes file:
 
 ```php
Route::get('/', function () {
    // Add static class as string
    Route2Class::addClass('homepage');
    
    // Add class from anonymous function
    Route2Class::addClass(function() {
        // Your custom logic goes here
        return 'my-anon-class-name';
    });
    
    return view('welcome');
});
```

You can call the `addClass` method anywhere - models, controllers, etc. Consider adding
generator files instead, as it promotes application maintainability and reduces technical debt.

## Demo Project

The demo project below follows the examples outlined above:

https://github.com/zschuessler/route2class-demo

## License

This is public domain. Do what you want.
