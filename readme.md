# Laravel Routes to Body Class

Quickly add body classes to your Laravel app based on rules you set.

Example of implementations:

1. Browsing as a guest might add `user-isGuest` as a class.
2. Browsing an admin panel might add `admin-panel` as a class.
3. All user profile routes might have `user-profile` as a class.

It's easy to write your own rules! You can either write your own generator classes
or use the ad-hoc API by interacting with the library singleton directly.

## Quickstart

**Require the package in your composer setup.**

```
composer require zschuessler/laravel-route-to-class
```

**Add the service provider to your app configuration**

Add the following line under the `providers` array key in *app/config.php*:

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

You now have access to a shared view variable function `$generate_route_body_classes`.
 
Use it in any of your views like so:

```php
{{$generate_route_body_classes()}}

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

Now add a reference to the generator in your `/config/route2class.php` configuration:

```
App\Providers\RouteToClass\UserTypeGenerator::class,
```

Now when you use the `{{$generate_route_to_classes()}}` line in a view template, you will
see the class `user-admin` - **neat**!

See this file for a real-life example:

https://github.com/zschuessler/laravel-route-to-class/blob/master/src/Generators/FullRoutePath.php

### Ad-Hoc Class Additions

You can interact with the body classes directly by calling the `addClass` method on the
provider.
 
Here's an example using the default Laravel project's routes file:
 
 ```php
Route::get('/', function () {
    // Add static class as string
    app()['route2class']->addClass('homepage');
    
    // Add class from anonymous function
    app()['route2class']->addClass(function() {
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