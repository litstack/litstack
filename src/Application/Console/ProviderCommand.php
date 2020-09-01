<?php

namespace Ignite\Application\Console;

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
