<?php

namespace Fjord\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Console\Migrations\RollbackCommand;

class FjordFormPermissions extends RollbackCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:form-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create permissions for forms that are set in _make_form_permissions migration.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $migrationsPath = app()->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR;
        $migrationPath = Collection::make($migrationsPath)
            ->flatMap(function ($path) {
                return File::glob($path . '*_make_form_permissions.php');
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
