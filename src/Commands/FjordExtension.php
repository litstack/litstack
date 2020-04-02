<?php

namespace AwStudio\Fjord\Commands;

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
    protected $description = 'This wizard will generate all the files needed for a new crud module';

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
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Fjord\Extensions';
    }
}
