<?php

namespace Ignite\Application\Console;

use Illuminate\Routing\Console\MiddlewareMakeCommand;

class MiddlewareCommand extends MiddlewareMakeCommand
{
    use Concerns\ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:middleware';
}
