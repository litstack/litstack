<?php

namespace Ignite\Support;

use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

trait LoadsLivewireComponents
{
    /**
     * Load livewire components from the given path.
     *
     * @param  string $namespace
     * @param  string $path
     * @param  string $refix
     * @return void
     */
    protected function loadLivewireComponentsFrom($namespace, $path, $prefix)
    {
        foreach ($this->getFlatComponentFiles($path) as $file) {
            if ($file->isDir()) {
                continue;
            }

            if ($file->getExtension() != 'php') {
                continue;
            }

            $class = $this->guessClassName($file, $namespace, $path);

            if (! class_exists($class) || ! is_subclass_of($class, Component::class)) {
                continue;
            }

            Livewire::component(
                $this->getComponentName($class, $namespace, $prefix), $class
            );
        }
    }

    /**
     * Get namespace from class name.
     *
     * @param  string $class
     * @param  string $namespace
     * @param  string $prefix
     * @return string
     */
    protected function getComponentName($class, $namespace, $prefix)
    {
        return $prefix.'::'.collect(explode('\\', str_replace($namespace.'\\', '', $class)))
            ->map(fn ($item) => Str::snake($item))->implode('.');
    }

    /**
     * Guess livewire component class name.
     *
     * @param  string $file
     * @param  string $namespace
     * @param  string $path
     * @return string
     */
    protected function guessClassName(string $file, $namespace, $path)
    {
        return $namespace.str_replace('/', '\\', str_replace([$path, '.php'], '', $file));
    }

    /**
     * Return flat recursive livewire component files.
     *
     * @return RecursiveIteratorIterator|array
     */
    public function getFlatComponentFiles($path)
    {
        if (! realpath($path)) {
            return [];
        }

        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );
    }
}
