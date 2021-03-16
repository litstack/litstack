<?php

namespace Ignite\Application\Console;

use Illuminate\Foundation\Console\ResourceMakeCommand;

class ResourceCommand extends ResourceMakeCommand
{
    use Concerns\ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return lit_vendor_path('stubs/crud.resource.stub');
    }
}
