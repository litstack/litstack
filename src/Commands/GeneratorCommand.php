<?php

namespace Fjord\Commands;

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
        return 'FjordApp\\';
    }

    /**
     * Get fjord base path.
     *
     * @return string
     */
    protected function getFjordPath()
    {
        return base_path('fjord/app');
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->getFjordPath().'/'.str_replace('\\', '/', $name).'.php';
    }
}
