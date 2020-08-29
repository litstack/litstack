<?php

namespace Ignite\Application\Console;

use Illuminate\Foundation\Console\CastMakeCommand;

class CastCommand extends CastMakeCommand
{
    use Concerns\ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:cast';
}
