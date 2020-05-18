<?php

namespace Fjord\Test;

use Fjord\FjordServiceProvider;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends Orchestra
{
    use SetupDatabase, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        File::deleteDirectory(__DIR__ . '/laravel');
        File::makeDirectory(__DIR__ . '/laravel');

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        // The given order is important since fjord migrations require certain 
        // depency migrations to be executed before. 
        return [
            \Cviebrock\EloquentSluggable\ServiceProvider::class,
            \Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
            \Spatie\Permission\PermissionServiceProvider::class,
            FjordServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'fjord-test',
            'username' => 'root',
            'password' => 'root'
        ]);
    }
}
