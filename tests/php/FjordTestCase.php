<?php

namespace FjordTest;

use Fjord\Support\Facades\Fjord;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Fjord\Fjord\Discover\PackageDiscoverCommand;

trait FjordTestCase
{
    /**
     * Package providers.
     *
     * @var array
     */
    public static $packageProviders = [
        \Cviebrock\EloquentSluggable\ServiceProvider::class,
        \Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
        \Spatie\Permission\PermissionServiceProvider::class,
        \Astrotomic\Translatable\TranslatableServiceProvider::class,
        \Fjord\FjordServiceProvider::class
    ];

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
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return static::$packageProviders;
    }

    /**
     * Setup app environment
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $_ENV['APP_DEBUG'] = true;
        $_ENV['APP_KEY'] = 'base64:tmj1IBe6YT/TWBgyiLHl0gC7lSHGR8E4nrgZwGYcJnI=';

        $config = $app['config'];
        $config->set('app.debug', $_ENV['APP_DEBUG']);
        $config->set('app.key', $_ENV['APP_KEY']);
        $config->set('database.default', 'test');
        $config->set('database.connections.test', [
            'driver'   => 'sqlite',
            'database' => $this->getDatabase(),
            'prefix'   => '',
        ]);
        $config->set('translatable.locales', [
            'en',
            'de'
        ]);
    }

    /**
     * Get sqlite database.
     *
     * @return string
     */
    protected function getDatabase()
    {
        return ':memory:';
    }

    /**
     * Discover installed packages and composer.json packages.
     *
     * @return void
     */
    protected function discoverFjordPackages()
    {
        $finder = new PackageDiscoverCommand;

        $vendorPath = realpath(__DIR__ . '/../../vendor');

        $manifest = array_merge(
            // Find packages in current composer json.
            $finder->filterFjordPackages([
                json_decode(File::get(__DIR__ . '/../../composer.json'), true)
            ]),
            // Find packages in current composer json.
            $finder->findFjordPackages($vendorPath)
        );

        $finder->write($manifest);
    }
}
