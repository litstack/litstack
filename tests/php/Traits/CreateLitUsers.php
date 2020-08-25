<?php

namespace Tests\Traits;

use Ignite\User\Models\User;

trait CreateLitUsers
{
    /**
     * Lit user password.
     *
     * @var string
     */
    protected $UserPassword = 'secret';

    /**
     * Lit user email's and their roles.
     *
     * @var array
     */
    protected $UsersToCreate = [
        'admin@admin.com' => 'admin',
        'user@user.com'   => 'user',
    ];

    /**
     * Created Lit users.
     *
     * @var array
     */
    protected $Users = [];

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
        foreach ($this->UsersToCreate as $email => $role) {

            // When running a browser test, the factory might not be registered.
            try {
                $user = factory(User::class)->make()->getAttributes();
            } catch (\InvalidArgumentException $e) {
                return;
            }

            unset($user['email']);
            $User = User::firstOrCreate(
                ['email' => $email],
                $user
            )->assignRole($role);

            if ($role == 'admin') {
                $this->admin = $User;
            }
            if ($role == 'user') {
                $this->user = $User;
            }
        }
        $this->Users = User::all();
    }
}
