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
}
