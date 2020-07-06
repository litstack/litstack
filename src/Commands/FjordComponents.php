<?php

namespace Fjord\Commands;

use Illuminate\Console\Command;

class FjordComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:components';

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
        foreach (fjord_app()->get('components')->all() as $name => $options) {
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
