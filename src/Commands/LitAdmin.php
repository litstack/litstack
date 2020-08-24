<?php

namespace Lit\Commands;

use Lit\User\Models\LitUser;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class LitAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:admin';

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
            $this->error('You may run lit:install before lit:admin.');

            return;
        }

        $username = $this->ask('Enter the admin username');
        $first_name = $this->ask('Enter the admin first name');
        $last_name = $this->ask('Enter the admin last name');
        $email = $this->ask('Enter the admin email');
        $password = $this->secret('Enter the admin password');

        if (LitUser::where('username', $username)->orWhere('email', $email)->exists()) {
            return;
        }

        $user = new LitUser([
            'username'   => $username,
            'email'      => $email,
            'first_name' => $first_name,
            'last_name'  => $last_name,
        ]);

        $user->password = bcrypt($password);
        $user->save();

        $user->assignRole('admin');

        $this->info('User has been created');
    }
}
