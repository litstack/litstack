<?php

namespace FjordTest;

use ReflectionClass;
use Fjord\FjordServiceProvider;
use Fjord\Support\Facades\Fjord;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as Orchestra;
use Fjord\Fjord\Discover\PackageDiscoverCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BackendTestCase extends Orchestra
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // ...
    }

    /**
     * Migrate dummy tables.
     *
     * @return void
     */
    protected function migrate()
    {
        $this->artisan('migrate');
        $this->loadMigrationsFrom(__DIR__ . '/TestSupport/migrations');
    }

    /**
     * Install Fjord.
     *
     * @return void
     */
    public function installFjord($force = false)
    {
        if (Fjord::installed() && !$force) {
            return;
        }
        Artisan::call('fjord:install');
        $this->discoverFjordPackages();
        $this->refreshApplication();
    }

    /**
     * Get package provider
     *
     * @param Application $app
     * @return void
     */
    protected function getPackageProviders($app)
    {
        // The given order is important since fjord migrations require certain 
        // depency migrations to be executed before. 
        return [
            \Cviebrock\EloquentSluggable\ServiceProvider::class,
            \Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
            \Spatie\Permission\PermissionServiceProvider::class,
            \Astrotomic\Translatable\TranslatableServiceProvider::class,
            FjordServiceProvider::class
        ];
    }

    /**
     * Setup app environment
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $config = $app['config'];
        $config->set('database.default', 'testbench');
        $config->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $config->set('translatable.locales', [
            'en',
            'de'
        ]);
    }

    /**
     * Discover installed packages and composer.json packages.
     *
     * @return void
     */
    protected function discoverFjordPackages()
    {
        $finder = new PackageDiscoverCommand;

        $vendorPath = realpath(__DIR__ . '/../vendor');

        $manifest = array_merge(
            // Find packages in current composer json.
            $finder->filterFjordPackages([
                json_decode(File::get(__DIR__ . '/../composer.json'), true)
            ]),
            // Find packages in current composer json.
            $finder->findFjordPackages($vendorPath)
        );

        $finder->write($manifest);
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
}
