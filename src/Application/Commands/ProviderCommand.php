<?php

namespace Ignite\Application\Commands;

use Illuminate\Foundation\Console\ProviderMakeCommand;

class ProviderCommand extends ProviderMakeCommand
{
    use Concerns\ManagesGeneration;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lit:provider';
}
