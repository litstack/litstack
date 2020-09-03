<?php

namespace Ignite\Application\Console;

use Illuminate\Foundation\Console\ComponentMakeCommand;
use Illuminate\Support\Str;

class ComponentCommand extends ComponentMakeCommand
{
    use Concerns\ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:component';

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        return Str::replaceFirst(
            'components',
            'lit::components',
            parent::buildClass($name)
        );
    }

    /**
     * Get the view name relative to the components directory.
     *
     * @return string view
     */
    protected function writeView()
    {
        return $this->mockBasePath(fn () => parent::writeView());
    }
}
