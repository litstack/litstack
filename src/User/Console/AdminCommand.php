<?php

namespace Ignite\User\Console;

use Ignite\Support\Facades\Lit;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AdminCommand extends Command
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

        $model = app(Lit::getUserModel());

        if ($model->where('username', $username)->orWhere('email', $email)->exists()) {
            $this->info('User already exists');

            return;
        }

        $user = new $model([
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
