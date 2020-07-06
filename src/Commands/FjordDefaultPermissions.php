<?php

namespace Fjord\Commands;

use Illuminate\Console\Command;

class FjordDefaultPermissions extends Command
{
    use Traits\RolesAndPermissions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:default-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createDefaultPermissions();
    }
}
