<?php

namespace Ignite\Crud\Console\Concerns;

use Illuminate\Support\Str;

trait CreatesMigration
{
    /**
     * Create migration file.
     *
     * @return void
     */
    protected function createMigration()
    {
        if ($this->migrationExists()) {
            $this->line('Migration for '.$this->table.' already exists.');

            return false;
        }

        $content = $this->files->get($this->migrationStubPath());

        $replace = [
            'DummyTranslationTablename' => $this->translationsTable,
            'DummyForeignId'            => Str::singular($this->table).'_id',
            'DummyClassname'            => $this->migrationClassName(),
            'DummyTablename'            => $this->table,
            'DummySlug'                 => "\n\t\t\t\$table->string('slug')->nullable();\n",
        ];

        if (! $this->slug) {
            $content = str_replace('DummySlug', '', $content);
        }

        foreach ($replace as $name => $value) {
            $content = str_replace($name, $value, $content);
        }

        $this->files->put($this->migrationPath(), $content);
        fix_file($this->migrationPath());

        $this->info('Migration created!');

        return true;
    }

    /**
     * Get migration class name.
     *
     * @return string
     */
    protected function migrationClassName()
    {
        return 'Create'.ucfirst(Str::plural($this->model)).($this->translatable ? 'Tables' : 'Table');
    }

    /**
     * Determines if the migration file already exists.
     *
     * @return bool
     */
    protected function migrationExists()
    {
        $files = scandir(database_path('migrations'));
        $migrationName = 'create_'.$this->table.'_table.php';

        foreach ($files as $file) {
            if (Str::endsWith($file, $migrationName)) {
                $this->migrationPath = database_path("migrations/{$file}");

                return true;
            }
        }

        return false;
    }
}
