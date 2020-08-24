<?php

namespace Tests\Traits;

use Lit\User\Models\LitUser;

trait CreateLitUsers
{
    /**
     * Lit user password.
     *
     * @var string
     */
    protected $litUserPassword = 'secret';

    /**
     * Lit user email's and their roles.
     *
     * @var array
     */
    protected $litUsersToCreate = [
        'admin@admin.com' => 'admin',
        'user@user.com'   => 'user',
    ];

    /**
     * Created Lit users.
     *
     * @var array
     */
    protected $litUsers = [];

    /**
     * Lit user with admin role.
     *
     * @var LitUser
     */
    protected $admin;

    /**
     * Lit user with user role.
     *
     * @var LitUser
     */
    protected $user;

    /**
     * Create laravel backup that is used to refresh.
     *
     * @return void
     */
    public function createLitUsers()
    {
        foreach ($this->litUsersToCreate as $email => $role) {

            // When running a browser test, the factory might not be registered.
            try {
                $user = factory(LitUser::class)->make()->getAttributes();
            } catch (\InvalidArgumentException $e) {
                return;
            }

            unset($user['email']);
            $litUser = LitUser::firstOrCreate(
                ['email' => $email],
                $user
            )->assignRole($role);

            if ($role == 'admin') {
                $this->admin = $litUser;
            }
            if ($role == 'user') {
                $this->user = $litUser;
            }
        }
        $this->litUsers = LitUser::all();
    }
}
