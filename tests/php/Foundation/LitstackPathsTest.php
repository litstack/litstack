<?php

namespace Tests\Foundation;

use Ignite\Foundation\Litstack;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

class LitstackPathsTest extends TestCase
{
    public function setUp(): void
    {
        $app = new Application;
        $this->litstack = $app['lit'] = new Litstack($app);
        Container::setInstance($app);
    }

    /** @test */
    public function test_basePath_method()
    {
        $this->assertSame(base_path('lit'), $this->litstack->basePath());
        $this->assertSame(base_path('lit/foo'), $this->litstack->basePath('foo'));
    }

    /** @test */
    public function test_path_method()
    {
        $this->assertSame(base_path('lit/app'), $this->litstack->path());
        $this->assertSame(base_path('lit/app/foo'), $this->litstack->path('foo'));
    }

    /** @test */
    public function test_resourcePath_method()
    {
        $this->assertSame(base_path('lit/resources'), $this->litstack->resourcePath());
        $this->assertSame(base_path('lit/resources/foo'), $this->litstack->resourcePath('foo'));
    }

    /** @test */
    public function test_langPath_method()
    {
        $this->assertSame(base_path('lit/resources/lang'), $this->litstack->langPath());
    }

    /** @test */
    public function test_vendorPath_method()
    {
        $this->assertSame(realpath(__DIR__.'/../../..'), $this->litstack->vendorPath());
        $this->assertSame(realpath(__DIR__.'/../../..').DIRECTORY_SEPARATOR.'foo', $this->litstack->vendorPath('foo'));
    }
}
