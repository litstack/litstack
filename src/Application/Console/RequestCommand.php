<?php

namespace Ignite\Application\Console;

use Illuminate\Foundation\Console\RequestMakeCommand;

class RequestCommand extends RequestMakeCommand
{
    use Concerns\ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:request';
}
