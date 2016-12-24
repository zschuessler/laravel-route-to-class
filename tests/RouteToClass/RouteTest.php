<?php

use Orchestra\Testbench\TestCase;
use Illuminate\Routing\Router;

/**
 * Class RouteTest
 */
class RouteTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            'Zschuessler\RouteToClass\ServiceProvider',
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Route2Class' => 'Zschuessler\RouteToClass\RouteToClass'
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['router']->get('/', [
            'as' => 'empty',
            'uses' => function () {
                return $this->getClass();
            }
        ]);
        $app['router']->get('example', [
            'as' => 'example',
            'uses' => function () {
                return $this->getClass();
            }
        ]);

        $app['router']->get('another-example', function () {
            return $this->getClass();
        })->name('another.example');

        $app['router']->group(['prefix' => 'admin'], function (Router $router) {
            $router->get('index', [
                'as' => 'admin.index',
                'uses' => function () {
                    return $this->getClass();
                }
            ]);

            $router->get('list', function () {
                return $this->getClass();
            })->name('admin.list');

            $router->get('additional-class', function () {
                Route2Class::addClass('extra');
                return $this->getClass();
            })->name('admin.additional');
        });
    }

    /**
     * @return mixed
     */
    private function getClass()
    {
        return \Route2Class::generateClassString();
    }

    /**
     * @test
     */
    public function has_example_route_class()
    {
        $response = $this->call('GET', '/example');
        $this->assertEquals('example', $response->getContent());
        $this->assertNotEquals('/example', $response->getContent());
    }

    /**
     * @test
     */
    public function has_named_example_route_class()
    {
        $response = $this->route('GET', 'another.example');
        $this->assertEquals('another-example', $response->getContent());
        $this->assertNotEquals('another.example', $response->getContent());
    }

    /**
     * @test
     */
    public function has_nested_call_class()
    {
        $response = $this->call('GET', 'admin/index');
        $this->assertEquals('admin-index', $response->getContent());
        $this->assertNotEquals('admin/index', $response->getContent());
    }

    /**
     * @test
     */
    public function has_nested_route_class()
    {
        $response = $this->route('GET', 'admin.list');
        $this->assertEquals('admin-list', $response->getContent());
        $this->assertNotEquals('admin.list', $response->getContent());
    }

    /**
     * @test
     */
    public function has_nested_additional_route_class()
    {
        $response = $this->route('GET', 'admin.additional');
        $this->assertNotEquals('admin.additional', $response->getContent());
        $this->assertContains('admin-additional-class', $response->getContent());
        $this->assertContains('extra', $response->getContent());
        $this->assertEquals('admin-additional-class extra', $response->getContent());
    }

    /**
     * @test
     */
    public function has_no_class()
    {
        $response = $this->route('GET', 'empty');
        $this->assertEmpty($response->getContent());

    }
}