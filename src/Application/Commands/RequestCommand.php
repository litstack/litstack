<?php

namespace Ignite\Application\Commands;

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
