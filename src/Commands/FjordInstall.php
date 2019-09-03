<?php

namespace AwStudio\Fjord\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class FjordInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This wizard will take you through the installation process';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem)
    {
        $this->vendorConfigs();
        $this->handleUserModel();
        $this->handleFjordPublishable();
        $this->handleFjordResources();

        $role = Role::firstOrCreate(['name' => 'admin']);
        $role = Role::firstOrCreate(['name' => 'user']);

        $this->call('storage:link');

        $this->info('installation complete - run fjord:admin to create an admin user');
    }

    public function handleFjordResources()
    {
        if(is_dir(fjord_resource_path())) {
            return;
        }
        File::copyDirectory(fjord_path('publish/fjord'), resource_path('fjord'));
    }

    /**
     * Publish all relevant vendor config files and edit them as needed
     *
     * @return void
     */
    private function vendorConfigs()
    {
        $migrationFiles = array_keys(app()['migrator']->getMigrationFiles(database_path('migrations')));

        $this->call('vendor:publish', [
            '--provider' => "Cviebrock\EloquentSluggable\ServiceProvider"
        ]);

        $this->call('vendor:publish', [
            '--tag' => "translatable"
        ]);

        $mediaMatch = collect($migrationFiles)->filter(function($file) {
            return \Str::endsWith($file, 'create_media_table');
        })->first();
        if(! $mediaMatch) {
            $this->call('vendor:publish', [
                '--provider' => "Spatie\MediaLibrary\MediaLibraryServiceProvider",
                '--tag' => "migrations"
            ]);
        }

        $this->call('vendor:publish', [
            '--provider' => "Spatie\Permission\PermissionServiceProvider",
            '--tag' => "migrations"
        ]);

        // migrate vendor tables
        $this->call('migrate');

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
     * Edits and moves the Laravel user model
     *
     * @return void
     */
    private function handleUserModel()
    {
        if (file_exists(app_path('User.php'))) {
            $str = file_get_contents(app_path('User.php'));

            if ($str !== false) {
                $str = str_replace('namespace App;', "namespace App\Models;", $str);
                $str = str_replace("use Illuminate\Foundation\Auth\User as Authenticatable;", "use AwStudio\Fjord\Fjord\Models\User as FjordUser;", $str);
                $str = str_replace('extends Authenticatable', "extends FjordUser", $str);


                file_put_contents(app_path('User.php'), $str);
                if(!\File::exists('app/Models')){
                    \File::makeDirectory('app/Models');
                }
                $move = File::move(app_path('User.php'), app_path('Models/User.php'));
            }
        }

        // correct namespace where User is being used
        $files = [
            app_path('Http/Controllers/Auth/RegisterController.php'),
            config_path('auth.php'),
            config_path('services.php'),
            base_path('database/factories/UserFactory.php')
        ];
        foreach ($files as $file) {
            $str = file_get_contents($file);
            $str = str_replace('App\User', "App\Models\User", $str);
            file_put_contents($file, $str);
        }
    }

    /**
     * Publish Fjord config and assets
     *
     * @return void
     */
    private function handleFjordPublishable()
    {
        $this->call('vendor:publish', [
            '--provider' => "AwStudio\Fjord\FjordServiceProvider"
        ]);

        // migrate tables
        $this->call('migrate');
    }
}
