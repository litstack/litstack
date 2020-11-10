<?php

namespace Ignite\Crud\Console;

use Ignite\Application\Console\Concerns\ManagesGeneration;
use Ignite\Console\GeneratorCommand;
use Illuminate\Support\Str;

class FilterCommand extends GeneratorCommand
{
    use ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new custom CRUD filter';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Filter';

    /**
     * Handle lit:filter command.
     *
     * @return void
     */
    public function handle()
    {
        $result = parent::handle();

        $this->fixGeneratedFile();

        return $result;
    }

    /**
     * Build vue component.
     *
     * @param  string $name
     * @return string
     */
    protected function buildComponent($name)
    {
        $replace = [
            'DummyComponent' => $name,
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            $this->files->get($this->getComponentStub())
        );
    }

    /**
     * Determiens if vue component already exists.
     *
     * @return bool
     */
    protected function componentExists()
    {
        return $this->files->exists($this->componentPath());
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return lit_vendor_path('stubs/filter.stub');
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     * @return string
     */
    // protected function buildClass($name)
    // {
    //     $replace = [
    //         //'DummyComponent' => $this->componentName($tag = true),
    //     ];

    //     return str_replace(
    //         array_keys($replace),
    //         array_values($replace),
    //         parent::buildClass($name)
    //     );
    // }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Filters';
    }
}
