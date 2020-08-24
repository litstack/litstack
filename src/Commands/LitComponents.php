<?php

namespace Lit\Commands;

use Illuminate\Console\Command;

class LitComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:components';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all registered components that can be extended.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $components = [];
        foreach (lit_app()->get('components')->all() as $name => $options) {
            $component = component($name);
            $components[] = [
                'name'  => $name,
                'props' => implode(', ', array_keys($component->getAvailableProps())),
                'slots' => implode(', ', array_keys($component->getAvailableSlots())),
            ];
        }

        $this->table([
            'Name',
            'Props',
            'Slots',
        ], $components);
    }
}
