<?php

namespace Ignite\Application\Commands;

use Illuminate\Foundation\Console\JobMakeCommand;

class JobCommand extends JobMakeCommand
{
    use Concerns\ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:job';
}
