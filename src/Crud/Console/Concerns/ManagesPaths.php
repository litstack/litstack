<?php

namespace Ignite\Crud\Console\Concerns;

trait ManagesPaths
{
    /**
     * Path to the migration.
     *
     * @var string
     */
    protected $migrationPath;

    /**
     * Get path to the translations Model.
     *
     * @return string
     */
    protected function translationModelPath()
    {
        return app_path("Models/Translations/{$this->model}Translation.php");
    }

    /**
     * Get path to the Model.
     *
     * @return string
     */
    protected function modelPath()
    {
        return app_path("Models/{$this->model}.php");
    }

    /**
     * Get path to the Migration.
     *
     * @return string
     */
    protected function migrationPath()
    {
        if ($this->migrationPath) {
            return $this->migrationPath;
        }

        $timestamp = str_replace(' ', '_', str_replace('-', '_', str_replace(':', '', now())));

        return $this->migrationPath = database_path(
            "migrations/{$timestamp}_create_{$this->table}_table.php"
        );
    }

    /**
     * Get path to the controller.
     *
     * @return string
     */
    protected function controllerPath()
    {
        return lit()->path("Http/Controllers/Crud/{$this->controller}.php");
    }

    /**
     * Get path to the config.
     *
     * @return string
     */
    protected function configPath()
    {
        return lit()->path("Config/Crud/{$this->config}.php");
    }

    /**
     * Get path to the migration stub.
     *
     * @return string
     */
    protected function migrationStubPath()
    {
        $path = lit_vendor_path('stubs/crud.migration');

        return $this->translatable
            ? "{$path}.translatable.stub"
            : "{$path}.stub";
    }

    /**
     * Get path to the controller stub.
     *
     * @return string
     */
    protected function controllerStubPath()
    {
        return lit_vendor_path('stubs/CrudController.stub');
    }

    /**
     * Get path to the config stub.
     *
     * @return string
     */
    protected function configStubPath()
    {
        return lit_vendor_path('stubs/CrudConfig.stub');
    }

    /**
     * Get path to the config stub.
     *
     * @return string
     */
    protected function modelStubPath()
    {
        return lit_vendor_path('stubs/crud.model.stub');
    }

    /**
     * Get path to the config stub.
     *
     * @return string
     */
    protected function modelSluggableStubPath()
    {
        return lit_vendor_path('stubs/crud.model.sluggable.stub');
    }
}
