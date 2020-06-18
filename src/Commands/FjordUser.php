<?php

namespace Fjord\Commands;

use Illuminate\Console\Command;
use Fjord\Support\Facades\Fjord;
use Fjord\User\Models\FjordUser as FjordUserModel;

class FjordUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:user';

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
        if (!Fjord::installed()) {
            $this->error('You may run fjord:install before fjord:user.');
            return;
        }

        $username = $this->ask('Enter the username');
        $first_name = $this->ask('Enter the first name');
        $last_name = $this->ask('Enter the last name');
        $email = $this->ask('Enter the email');
        $password = $this->secret('Enter the password');

        $user = FjordUserModel::firstOrCreate([
            'username' => $username,
            'email' => $email,
        ], [
            'password' => bcrypt($password),
            'first_name' => $first_name,
            'last_name' => $last_name,
        ]);

        $user->assignRole('user');

        $this->info('User has been created');
    }
}
