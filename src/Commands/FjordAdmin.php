<?php

namespace AwStudio\Fjord\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

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
        if(! Role::where('name', 'admin')->exists()) {
            $this->error('You may run fjord:install before fjord:admin.');
            return;
        }

        $name = $this->ask('enter the admin username');
        $email = $this->ask('enter the admin email');
        $password = $this->secret('enter the admin password');

        $user = User::firstOrCreate([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        $user->assignRole('admin');

        $this->info('User has been created');
    }


}
