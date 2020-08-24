<?php

namespace Lit\Commands;

use Lit\Support\Facades\Lit;
use Lit\User\Models\LitUser as LitUserModel;
use Illuminate\Console\Command;

class LitUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This wizard will generate an user for you';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! Lit::installed()) {
            $this->error('You may run lit:install before lit:user.');

            return;
        }

        $username = $this->ask('Enter the username');
        $first_name = $this->ask('Enter the first name');
        $last_name = $this->ask('Enter the last name');
        $email = $this->ask('Enter the email');
        $password = $this->secret('Enter the password');

        if (LitUserModel::where('username', $username)->orWhere('email', $email)->exists()) {
            return;
        }

        $user = new LitUserModel([
            'username'   => $username,
            'email'      => $email,
            'first_name' => $first_name,
            'last_name'  => $last_name,
        ]);

        $user->password = bcrypt($password);
        $user->save();

        $user->assignRole('user');

        $this->info('User has been created');
    }
}
