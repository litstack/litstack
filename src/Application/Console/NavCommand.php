<?php

namespace Ignite\Application\Console;

use Closure;
use Ignite\Application\Navigation\PresetFactory;
use Illuminate\Console\Command;

class NavCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:nav';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all navigation entry presets';

    /**
     * Navigation preset factory instance.
     *
     * @var PresetFactory
     */
    protected $factory;

    /**
     * Create new NavCommand instance.
     *
     * @param  PresetFactory $factory
     * @return void
     */
    public function __construct(PresetFactory $factory)
    {
        parent::__construct();

        $this->factory = $factory;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entries = [];
        foreach ($this->factory->all() as $preset) {
            $link = $preset['link'] instanceof Closure
                ? $preset['link']()
                : $preset['link'];

            $entries[] = [
                'keys' => $keys,
                'link' => $link,
            ];
        }

        $this->table([
            'Keys', 'Link',
        ], $entries);
    }
}
