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
            $this->laravel['path'], lit()->getPath().'/app', parent::getPath($name)
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
        $this->laravel->setBasePath(lit()->getPath());

        $result = $closure();

        $this->laravel->setBasePath($original);

        return $result;
    }
}
