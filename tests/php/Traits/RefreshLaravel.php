<?php

namespace FjordTest\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;

trait RefreshLaravel
{
    /**
     * Create laravel backup that is used to refresh.
     *
     * @return void
     */
    public static function createLaravelBackup()
    {
        // Creating backup of testbench laravel sceleton.
        if (realpath(__DIR__ . '/../../resources/laravel/app')) {
            return;
        }
        (new Filesystem)->copyDirectory(
            __DIR__ . '/../../../vendor/orchestra/testbench-core/laravel',
            __DIR__ . '/../../resources/laravel'
        );
    }

    /**
     * Refresh laravel.
     *
     * @return void
     */
    public static function refreshLaravel()
    {
        $file = new Filesystem;
        $file->deleteDirectory(realpath(__DIR__ . '/../../../vendor/orchestra/testbench-core/laravel'));
        $file->copyDirectory(
            __DIR__ . '/../../resources/laravel',
            __DIR__ . '/../../../vendor/orchestra/testbench-core/laravel',
        );
    }

    /**
     * Get base path.
     *
     * @return string
     */
    protected function getBasePath()
    {
        return realpath(__DIR__ . '/../../../vendor/orchestra/testbench-core/laravel');
    }

    /**
     * Fix migrations.
     *
     * @return void
     */
    public function fixMigrations()
    {
        File::cleanDirectory(database_path('migrations'));
        $this->loadMigrationsFrom(__DIR__ . '/../../../vendor/orchestra/testbench-dusk/laravel/database/migrations');
    }
}
