<?php

namespace Fjord\Commands;

use Illuminate\Console\GeneratorCommand;

class FjordExtension extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:extension {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new extension for any Fjord modules.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'FjordExtension';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return fjord_path('stubs/Extension.stub');
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'FjordApp';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name').'Extension');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Extensions';
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    public function getPath($name)
    {
        $name = $this->getNameInput();

        return base_path("fjord/app/Extensions/{$name}.php");
    }
}
