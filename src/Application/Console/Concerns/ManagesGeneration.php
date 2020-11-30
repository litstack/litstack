<?php

namespace Ignite\Application\Console\Concerns;

use Closure;
use Illuminate\Support\Str;

trait ManagesGeneration
{
    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return lit()->getNamespace();
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        return Str::replaceFirst(
            $this->laravel['path'],
            lit()->getPath().'/app',
            parent::getPath($name)
        );
    }

    /**
     * Mock base path.
     *
     * @param  Closure $closure
     * @return mixed
     */
    protected function mockBasePath(Closure $closure)
    {
        $original = $this->laravel->basePath();
        $this->laravel->setBasePath(lit()->basePath());

        $result = $closure();

        $this->laravel->setBasePath($original);

        return $result;
    }

    /**
     * Get the litstack view directory path.
     *
     * @param  string $path
     * @return string
     */
    protected function viewPath($path = '')
    {
        return lit_resource_path('views').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Fix the generated file.
     *
     * @return void
     */
    protected function fixGeneratedFile()
    {
        $path = $this->getPath(
            $this->qualifyClass($this->getNameInput())
        );

        fix_file($path);
    }
}
