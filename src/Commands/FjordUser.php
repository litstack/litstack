<?php

namespace Fjord\Commands;

use Illuminate\Console\Command;
use Fjord\User\Models\FjordUser as User;
use Spatie\Permission\Models\Role;

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
        if (!Role::where('name', 'user')->exists()) {
            $this->error('You may run fjord:install before fjord:user.');
            return;
        }

        $name = $this->ask('enter the username');
        $email = $this->ask('enter the email');
        $password = $this->secret('enter the password');

        $user = User::firstOrCreate([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        $user->assignRole('user');

        $this->info('User has been created');
    }
}
