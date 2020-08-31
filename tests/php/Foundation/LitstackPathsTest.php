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
        $this->assertSame(base_path('lit'.DIRECTORY_SEPARATOR.'foo'), $this->litstack->basePath('foo'));
    }

    /** @test */
    public function test_path_method()
    {
        $this->assertSame(base_path('lit'.DIRECTORY_SEPARATOR.'app'), $this->litstack->path());
        $this->assertSame(base_path('lit'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'foo'), $this->litstack->path('foo'));
    }

    /** @test */
    public function test_resourcePath_method()
    {
        $this->assertSame(base_path('lit'.DIRECTORY_SEPARATOR.'resources'), $this->litstack->resourcePath());
        $this->assertSame(base_path('lit'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'foo'), $this->litstack->resourcePath('foo'));
    }

    /** @test */
    public function test_langPath_method()
    {
        $this->assertSame(base_path('lit'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'lang'), $this->litstack->langPath());
    }

    /** @test */
    public function test_vendorPath_method()
    {
        $this->assertSame(realpath(__DIR__.'/../../..'), $this->litstack->vendorPath());
        $this->assertSame(realpath(__DIR__.'/../../..').DIRECTORY_SEPARATOR.'foo', $this->litstack->vendorPath('foo'));
    }
}
