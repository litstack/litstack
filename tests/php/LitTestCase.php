<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Lit\Foundation\Discover\PackageDiscoverCommand;
use Lit\Support\Facades\Lit;
use Tests\Traits\ActingAsLitUserMock;
use Tests\Traits\CreateLitUsers;
use Tests\Traits\InteractsWithConfig;
use Tests\Traits\InteractsWithCrud;
use Tests\Traits\RefreshLaravel;

trait LitTestCase
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
        \Lit\LitServiceProvider::class,
    ];

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        parent::setUpTraits();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[RefreshLaravel::class])) {
            $this->fixMigrations();
        }
        if (isset($uses[CreateLitUsers::class])) {
            $this->createLitUsers();
        }
        if (isset($uses[InteractsWithConfig::class])) {
            $this->overrideConfigLoaderSingleton();
        }
        if (isset($uses[InteractsWithCrud::class])) {
            $this->setUpCrud();
        }
        if (isset($uses[ActingAsLitUserMock::class])) {
            $this->actingAsLitUserMock();
        }
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function tearDownTraits()
    {
        //
    }

    protected static function setUpBeforeClassTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[RefreshLaravel::class])) {
            static::createLaravelBackup();
        }
    }

    protected static function tearDownAfterClassTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[RefreshLaravel::class])) {
            static::refreshLaravel();
        }
    }

    /**
     * Migrate dummy tables.
     *
     * @return void
     */
    protected function migrate()
    {
        $this->artisan('migrate');
        $this->loadMigrationsFrom(__DIR__.'/TestSupport/migrations');
    }

    /**
     * Install Lit.
     *
     * @return void
     */
    public function installLit($force = false)
    {
        if (Lit::installed() && ! $force) {
            return;
        }
        Artisan::call('lit:install');
        $this->discoverLitPackages();
        $this->refreshApplication();
    }

    /**
     * Get package provider.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return static::$packageProviders;
    }

    /**
     * Setup app environment.
     *
     * @param Application $app
     *
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
            'de',
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
    protected function discoverLitPackages()
    {
        $finder = new PackageDiscoverCommand();

        $vendorPath = realpath(__DIR__.'/../../vendor');

        $manifest = array_merge(
            // Find packages in current composer json.
            $finder->filterLitPackages([
                json_decode(File::get(__DIR__.'/../../composer.json'), true),
            ]),
            // Find packages in current composer json.
            $finder->findLitPackages($vendorPath)
        );

        $finder->write($manifest);
    }
}
