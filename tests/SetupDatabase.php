<?php

namespace Fjord\Test;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Fjord\Test\TestSupport\Database\TestMigrator;

trait SetupDatabase
{
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
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    protected function setUpDatabase()
    {
        foreach ($this->getPackageProviders($this->app) as $provider) {
            $this->migratePackage($provider);
        }
        $this->artisan('migrate', ['--database' => 'testing']);

        with(new TestMigrator($this->app))->migrate();
    }
}
