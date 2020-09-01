<?php

namespace Ignite\Auth\Console;

use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class PermissionsCommand extends RollbackCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'lit:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create permissions that are set in the ______________________make_permissions migration.';

    /**
     * Filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create new PermissionsCommand instance.
     *
     * @param  Migrator   $migrator
     * @param  Filesystem $files
     * @return void
     */
    public function __construct(Migrator $migrator, Filesystem $files)
    {
        parent::__construct($migrator);

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migrationsPath = database_path('migrations');
        $migrationPath = Collection::make($migrationsPath)
            ->flatMap(function ($path) {
                return File::glob($path.'/*_make_permissions.php');
            })->first();
        $files = [$migrationPath];

        $this->migrator->requireFiles($files);
        $migration = $this->migrator->resolve(
            $name = $this->migrator->getMigrationName($migrationPath)
        );
        $this->line("<comment>Rolling back:</comment> {$name}");
        $migration->down();
        $this->line("<comment>Migrating:</comment> {$name}");
        $migration->up();
        $this->line("<info>Migrating:</info> {$name}");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
