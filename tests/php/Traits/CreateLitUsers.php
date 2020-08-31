<?php

namespace Tests\Traits;

use Lit\Models\User;

trait CreateLitUsers
{
    /**
     * Lit user password.
     *
     * @var string
     */
    protected $userPassword = 'secret';

    /**
     * Lit user email's and their roles.
     *
     * @var array
     */
    protected $usersToCreate = [
        'admin@admin.com' => 'admin',
        'user@user.com'   => 'user',
    ];

    /**
     * Created Lit users.
     *
     * @var array
     */
    protected $users = [];

    /**
     * Lit user with admin role.
     *
     * @var User
     */
    protected $admin;

    /**
     * Lit user with user role.
     *
     * @var User
     */
    protected $user;

    /**
     * Create laravel backup that is used to refresh.
     *
     * @return void
     */
    public function CreateLitUsers()
    {
        foreach ($this->usersToCreate as $email => $role) {

            // When running a browser test, the factory might not be registered.
            try {
                $user = factory(User::class)->make()->getAttributes();
            } catch (\InvalidArgumentException $e) {
                return;
            }

            unset($user['email']);
            $user = User::firstOrCreate(
                ['email' => $email],
                $user
            )->assignRole($role);

            if ($role == 'admin') {
                $this->admin = $user;
            }
            if ($role == 'user') {
                $this->user = $user;
            }
        }
        $this->user = User::all();
    }
}
