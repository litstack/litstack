<?php

namespace Fjord\Commands;

use Illuminate\Console\Command;
use Fjord\Support\Facades\Package;

class FjordNav extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:nav';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all navigation entry presets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

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
                $entries[] = [
                    'package' => $packageName,
                    'key' => $key,
                    'link' => $preset['link']
                ];
            }
        }

        $this->table([
            'Package',
            'Key',
            'Link'
        ], $entries);
    }
}
