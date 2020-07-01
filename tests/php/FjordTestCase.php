<?php

namespace FjordTest;

use Fjord\Fjord\Discover\PackageDiscoverCommand;
use Fjord\Support\Facades\Fjord;
use FjordTest\Traits\CreateFjordUsers;
use FjordTest\Traits\InteractsWithConfig;
use FjordTest\Traits\InteractsWithCrud;
use FjordTest\Traits\RefreshLaravel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

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
        \Fjord\FjordServiceProvider::class,
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
        if (isset($uses[CreateFjordUsers::class])) {
            $this->createFjordUsers();
        }
        if (isset($uses[InteractsWithConfig::class])) {
            $this->overrideConfigLoaderSingleton();
        }
        if (isset($uses[InteractsWithCrud::class])) {
            $this->setUpCrud();
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
     * Install Fjord.
     *
     * @return void
     */
    public function installFjord($force = false)
    {
        if (Fjord::installed() && ! $force) {
            return;
        }
        Artisan::call('fjord:install');
        $this->discoverFjordPackages();
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
    protected function discoverFjordPackages()
    {
        $finder = new PackageDiscoverCommand();

        $vendorPath = realpath(__DIR__.'/../../vendor');

        $manifest = array_merge(
            // Find packages in current composer json.
            $finder->filterFjordPackages([
                json_decode(File::get(__DIR__.'/../../composer.json'), true),
            ]),
            // Find packages in current composer json.
            $finder->findFjordPackages($vendorPath)
        );

        $finder->write($manifest);
    }
}
