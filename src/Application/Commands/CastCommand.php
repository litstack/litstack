<?php

namespace Ignite\Application\Commands;

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
