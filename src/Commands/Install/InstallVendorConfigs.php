<?php

namespace Fjord\Commands\Install;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

trait InstallVendorConfigs
{
    /**
     * Publish all relevant vendor config files and edit them as needed
     *
     * @return void
     */
    protected function vendorConfigs()
    {
        $this->info('publishing vendor configs');

        $migrationFiles = array_keys(app()['migrator']->getMigrationFiles(database_path('migrations')));

        // Laravel Packages.
        $this->vendorSluggable();
        $this->vendorTranslatable();
        $this->vendorMediaLibrary($migrationFiles);
        $this->vendorPermissions();

        // Migrate vendor tables.
        if (App::environment(['local', 'staging'])) {
            $this->callSilent('migrate');
        } else {
            $this->call('migrate');
        }
    }

    protected function vendorSluggable()
    {
        $this->callSilent('vendor:publish', [
            '--provider' => "Cviebrock\EloquentSluggable\ServiceProvider"
        ]);
    }

    protected function vendorTranslatable()
    {
        $this->callSilent('vendor:publish', [
            '--tag' => "translatable"
        ]);

        $replace = file_get_contents(config_path('translatable.php'));
        $replace = str_replace(
            "'fr',
        'es' => [
            'MX', // mexican spanish
            'CO', // colombian spanish
        ],",
            "'de'",
            $replace
        );
        File::put(config_path('translatable.php'), $replace);

        // set correct namespace for translation models
        $search = "'translation_model_namespace' => null,";
        $replace = "'translation_model_namespace' => 'App\Models\Translations',";

        $str = file_get_contents(config_path('translatable.php'));

        if ($str !== false) {
            $str = str_replace($search, $replace, $str);
            file_put_contents(config_path('translatable.php'), $str);
        }
    }

    /**
     * Publish medialibrary.
     *
     * @param array $migrationFiles
     * @return void
     */
    protected function vendorMediaLibrary($migrationFiles)
    {
        // If media migration exists, skip
        $mediaMatch = collect($migrationFiles)->filter(function ($file) {
            return Str::endsWith($file, 'create_media_table');
        })->first();
        // TODO: ask Jannes about this.
        if (!$mediaMatch) {
            $this->callSilent('vendor:publish', [
                '--provider' => "Spatie\MediaLibrary\MediaLibraryServiceProvider",
                '--tag' => "migrations"
            ]);
        }
        $this->callSilent('vendor:publish', [
            '--provider' => "Spatie\MediaLibrary\MediaLibraryServiceProvider",
            '--tag' => "config"
        ]);
        $content = file_get_contents(config_path(medialibrary_config_key() . '.php'));
        $content = str_replace(
            'Spatie\MediaLibrary\Models\Media::class',
            'Fjord\Crud\Models\Media::class',
            $content
        );
        File::put(config_path(medialibrary_config_key() . '.php'), $content);
    }

    /**
     * Publish permissions.
     *
     * @return void
     */
    protected function vendorPermissions()
    {
        $this->callSilent('vendor:publish', [
            '--provider' => \Spatie\Permission\PermissionServiceProvider::class,
            '--tag' => "migrations"
        ]);

        $migrationsPath = app()->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR;
        $migration = Collection::make($migrationsPath)
            ->flatMap(function ($path) {
                return File::glob($path . '*_create_permission_tables.php');
            })->first();
        $name = '2020_00_00_000000_create_permission_tables.php';
        if ($name == basename($migration)) {
            return;
        }
        File::move($migration, $migrationsPath . $name);
    }
}
