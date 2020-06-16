<?php

namespace Fjord\Commands;

use Illuminate\Console\GeneratorCommand as LaravelGeneratorCommand;

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
}
