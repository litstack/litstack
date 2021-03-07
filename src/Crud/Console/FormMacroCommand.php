<?php

namespace Ignite\Crud\Console;

use Ignite\Application\Console\Concerns\ManagesGeneration;
use Ignite\Console\GeneratorCommand;
use Illuminate\Support\Str;

class FormMacroCommand extends GeneratorCommand
{
    use ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:form-macro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Form Macro class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Form Macro';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return lit_vendor_path('stubs/form.macro.stub');
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [
            'DummyType' => $this->argument('name'),
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Macros\Form';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        if (! Str::endsWith($name, 'Macro')) {
            $name = Str::studly($name).'Macro';
        }

        return parent::qualifyClass($name);
    }
}
