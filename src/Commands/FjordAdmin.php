<?php

namespace Fjord\Commands;

use Fjord\User\Models\FjordUser;
use Illuminate\Console\Command;
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! Role::where('name', 'admin')->exists()) {
            $this->error('You may run fjord:install before fjord:admin.');

            return;
        }

        $username = $this->ask('Enter the admin username');
        $first_name = $this->ask('Enter the admin first name');
        $last_name = $this->ask('Enter the admin last name');
        $email = $this->ask('Enter the admin email');
        $password = $this->secret('Enter the admin password');

        $user = FjordUser::firstOrCreate([
            'username' => $username,
            'email'    => $email,
        ], [
            'password'   => bcrypt($password),
            'first_name' => $first_name,
            'last_name'  => $last_name,
        ]);

        $user->assignRole('admin');

        $this->info('User has been created');
    }
}
