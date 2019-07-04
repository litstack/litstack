<?php

namespace AwStudio\Fjord\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FjordAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This wizard will generate an admin user for you';

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
        $name = $this->ask('enter the admin username');
        $email = $this->ask('enter the admin email');
        $password = $this->ask('enter the admin password');

        User::firstOrCreate([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        $this->info('User has been created');
    }


}
