<?php

use Orchestra\Testbench\TestCase;
use Zschuessler\RouteToClass\RouteToClass;

/**
 * Class MethodTest
 */
class MethodTest extends TestCase
{
    protected $rtc = null;

    protected function setUp()
    {
        parent::setUp();

        $this->rtc = new RouteToClass();
    }

    public function rtc()
    {
        return $this->rtc;
    }

    /**
     * @test
     */
    public function has_no_classes()
    {
        $this->rtc()->addClass('');

        $this->assertEmpty($this->rtc()->generateClassString());
    }

    /**
     * @test
     */
    public function has_single_class()
    {
        $this->rtc()->addClass('test');
        $this->assertEquals('test', $this->rtc()->generateClassString());
    }

    /**
     * @test
     */
    public function has_multiple_classes()
    {
        $this->rtc()->addClass('multiple');
        $this->rtc()->addClass('test');
        $this->rtc()->addClass('classes');

        $this->assertContains('multiple', $this->rtc()->generateClassString());
        $this->assertContains('test', $this->rtc()->generateClassString());
        $this->assertContains('classes', $this->rtc()->generateClassString());
        $this->assertEquals('multiple test classes', $this->rtc()->generateClassString());
    }

    /**
     * @test
     */
    public function can_add_numeric_class()
    {
        $this->rtc()->addClass(444);
        $this->assertEquals("444", $this->rtc()->generateClassString());
    }

    /**
     * @test
     */
    public function strips_slashes()
    {
        $this->rtc()->addClass('///Something \\else');
        $this->assertEquals('something-else', $this->rtc()->generateClassString());
        $this->assertNotEquals('///Something \\else', $this->rtc()->generateClassString());
    }
}
