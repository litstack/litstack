<?php

namespace Ignite\Crud\Console\Concerns;

trait CreatesConfig
{
    /**
     * Create migration file.
     *
     * @return void
     */
    protected function createConfig()
    {
        if ($this->configExists()) {
            $this->line("Config {$this->model}Config already exists.");

            return false;
        }

        $content = $this->files->get($this->configStubPath());

        $content = str_replace('DummyClassname', $this->model, $content);
        $content = str_replace('DummyTablename', $this->table, $content);

        $this->files->ensureDirectoryExists(dirname($this->configPath()));
        $this->files->put($this->configPath(), $content);
        fix_file($this->configPath());

        $this->info('config created');

        return $this;
    }

    /**
     * Determines if the config file already exists.
     *
     * @return bool
     */
    protected function configExists()
    {
        return $this->files->exists($this->configPath());
    }
}
