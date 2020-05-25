<?php

namespace FjordTest;

use Fjord\Commands\FjordExtend;
use ReflectionClass;
use Fjord\FjordServiceProvider;
use Fjord\Support\Facades\Fjord;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;
use Fjord\Fjord\Discover\PackageDiscoverCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Dusk\TestCase as OrchestraDuskTestCase;

class BackendTestCase extends OrchestraDuskTestCase
{
    use RefreshDatabase, FjordTestCase;

    public function setUp(): void
    {
        parent::setUp();

        // ...
    }

    /**
     * Pass thru all method except for the given names.
     *
     * @param mock $mock
     * @param mixed $class
     * @param array $without
     * @return void
     */
    protected function passthruAllExcept($mock, $class, array $without)
    {
        $methods = get_class_methods($class);
        foreach ($without as $method) {
            unset($methods[array_search($method, $methods)]);
        }
        $mock->shouldReceive(...$methods)->passthru();
    }

    /**
     * Calling protected or private class method.
     *
     * @param mixed $instance
     * @param string $method
     * @param array $params
     * @return mixed
     */
    protected function callUnaccessibleMethod($instance, string $method, array $params = [])
    {
        $class = get_class($instance);
        $class = new ReflectionClass($class);
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($instance, []);
    }

    /**
     * Resetting browser environment.
     */
    protected function setUpTheBrowserEnvironment()
    {
    }
    protected function registerShutdownFunction()
    {
    }
    public static function prepare()
    {
    }
    public static function setUpBeforeClass(): void
    {
    }
    public static function tearDownAfterClass(): void
    {
    }
}
