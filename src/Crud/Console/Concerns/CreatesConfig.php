<?php

namespace Ignite\Crud\Console\Concerns;

use Illuminate\Support\Str;

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

        foreach ([
            'DummyClassname' => $this->config,
            'DummyModelclass' => $this->model,
            'DummyControllerclass' => $this->controller,
            'DummyTablename' => $this->table,
            'DummySingularname' => $this->model,
            'DummyPluralname' => ucfirst(Str::plural($this->model)),
            'DummySlug' => Str::slug($this->table),
            'DummyNamesParameter' => $this->getNamesParameter(),
            'DummyNamesDoc' => $this->getNamesParameterDoc(),
        ] as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

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

    protected function getNamesParameter()
    {
        return "{$this->model} \$".lcfirst($this->model).' = null';
    }

    protected function getNamesParameterDoc()
    {
        return "@param {$this->model}|null ".lcfirst($this->model);
    }
}
