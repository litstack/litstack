<?php

namespace Ignite\Crud\Console\Concerns;

trait CreatesController
{
    /**
     * Create migration file.
     *
     * @return void
     */
    protected function createController()
    {
        if ($this->controllerExists()) {
            $this->line("Controller {$this->model}Controller already exists.");

            return false;
        }

        $content = $this->files->get($this->controllerStubPath());

        $content = str_replace('DummyClass', $this->controller, $content);
        $content = str_replace('DummyModelClass', "\\App\\Models\\{$this->model}", $content);
        $content = str_replace('DummyTableName', $this->table, $content);

        $this->files->ensureDirectoryExists(dirname($this->controllerPath()));
        $this->files->put($this->controllerPath(), $content);
        fix_file($this->controllerPath());

        $this->info('Controller created!');

        return true;
    }

    /**
     * Determines if the controller file already exists.
     *
     * @return bool
     */
    protected function controllerExists()
    {
        return $this->files->exists($this->controllerPath());
    }
}
