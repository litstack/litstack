<?php

namespace Fjord\Test;

use Fjord\FjordServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

trait SetupDatabase
{
    protected $migrated = false;

    public function migratePackage($provider)
    {
        $paths = ServiceProvider::pathsToPublish(
            $provider,
            'migrations'
        );
        if (empty($paths)) {
            return;
        }

        $migrationsPath =  __DIR__ . '/laravel/migrations/' . str_replace('\\', '-', $provider);

        // Create migrations folder.
        File::makeDirectory($migrationsPath, $mode = 0777, true, true);

        foreach ($paths as $from => $realTo) {
            $testTo = $migrationsPath . '/' . basename($realTo);

            if (is_dir($from)) {
                File::copyDirectory($from, $testTo);
                $this->loadMigrationsFrom($testTo);
            } else {
                File::copy($from, $testTo);

                $this->loadMigrationsFrom(dirname($testTo));
            }
        }
    }

    protected function migrateFjordAndPackages()
    {
        $this->artisan('migrate:reset', ['--force' => true]);

        foreach ($this->getPackageProviders($this->app) as $provider) {
            $this->migratePackage($provider);
        }
    }

    protected function migrateFjord()
    {
        $this->artisan('migrate:reset', ['--force' => true]);
        $this->migratePackage(\Spatie\Permission\PermissionServiceProvider::class);
        $this->migratePackage(Fjord\FjordServiceProvider::class);
    }

    protected function migrateSupportTables()
    {
        $this->loadMigrationsFrom(__DIR__ . '/TestSupport/migrations');
    }

    protected function migrateAll()
    {
        $this->migrateFjordAndPackages();
        $this->migrateSupportTables();
    }
}
