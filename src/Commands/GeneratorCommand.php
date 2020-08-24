<?php

namespace Lit\Commands;

use Illuminate\Console\GeneratorCommand as LaravelGeneratorCommand;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends LaravelGeneratorCommand
{
    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'LitApp\\';
    }

    /**
     * Get lit base path.
     *
     * @return string
     */
    protected function getLitPath()
    {
        return base_path('lit/app');
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->getLitPath().'/'.str_replace('\\', '/', $name).'.php';
    }
}
