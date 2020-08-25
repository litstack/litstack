<?php

namespace Ignite\Commands;

use Illuminate\Console\Command;

class LitAction extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:action {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an action.';

    /**
     * Type.
     *
     * @var string
     */
    protected $type = 'action';

    /**
     * Get stub path.
     *
     * @return string
     */
    public function getStub()
    {
        return lit_path('stubs/action.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Actions';
    }
}
