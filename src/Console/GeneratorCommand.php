<?php

namespace Ignite\Console;

use Ignite\Foundation\Litstack;
use Illuminate\Console\GeneratorCommand as LaravelGeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends LaravelGeneratorCommand
{
    /**
     * Litstack instance.
     *
     * @var Litstack
     */
    protected $litstack;

    /**
     * Create new GeneratorCommand instance.
     *
     * @param  Filesystem $files
     * @param  Litstack   $litstack
     * @return void
     */
    public function __construct(Filesystem $files, Litstack $litstack)
    {
        parent::__construct($files);

        $this->litstack = $litstack;
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->litstack->getNamespace();
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

        return $this->litstack->path(
            str_replace('\\', '/', $name).'.php'
        );
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
