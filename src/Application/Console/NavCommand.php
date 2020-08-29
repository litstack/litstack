<?php

namespace Ignite\Application\Console;

use Closure;
use Ignite\Support\Facades\Package;
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entries = [];
        foreach (Package::all() as $name => $package) {
            $packageName = Package::hasRootAccess($name) ? '' : $name;
            foreach ($package->getNavPresets() as $key => $preset) {
                $link = $preset['link'] instanceof Closure
                    ? $preset['link']()
                    : $preset['link'];
                $entries[] = [
                    'package' => $packageName,
                    'key'     => $key,
                    'link'    => $link,
                ];
            }
        }

        $this->table([
            'Package', 'Key', 'Link',
        ], $entries);
    }
}
