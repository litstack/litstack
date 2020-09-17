<?php

namespace Ignite\Crud\Console;

use Ignite\Application\Console\Concerns\ManagesGeneration;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class RepeatableCommand extends GeneratorCommand
{
    use ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:repeatable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new custom Repeatable class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repeatable';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return lit_vendor_path('stubs/repeatable.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repeatables';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        if (! Str::endsWith($name, 'Repeatable')) {
            $name = Str::studly($name).'Repeatable';
        }

        return parent::qualifyClass($name);
    }
}
