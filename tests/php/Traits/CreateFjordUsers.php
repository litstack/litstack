<?php

namespace FjordTest\Traits;

use Fjord\User\Models\FjordUser;

trait CreateFjordUsers
{
    /**
     * Fjord user password.
     *
     * @var string
     */
    protected $fjordUserPassword = 'secret';

    /**
     * Fjord user email's and their roles.
     *
     * @var array
     */
    protected $fjordUsersToCreate = [
        'admin@admin.com' => 'admin',
        'user@user.com'   => 'user',
    ];

    /**
     * Created Fjord users.
     *
     * @var array
     */
    protected $fjordUsers = [];

    /**
     * Fjord user with admin role.
     *
     * @var FjordUser
     */
    protected $admin;

    /**
     * Fjord user with user role.
     *
     * @var FjordUser
     */
    protected $user;

    /**
     * Create laravel backup that is used to refresh.
     *
     * @return void
     */
    public function createFjordUsers()
    {
        foreach ($this->fjordUsersToCreate as $email => $role) {

            // When running a browser test, the factory might not be registered.
            try {
                $user = factory(FjordUser::class)->make()->getAttributes();
            } catch (\InvalidArgumentException $e) {
                return;
            }

            unset($user['email']);
            $fjordUser = FjordUser::firstOrCreate(
                ['email' => $email],
                $user
            )->assignRole($role);

            if ($role == 'admin') {
                $this->admin = $fjordUser;
            }
            if ($role == 'user') {
                $this->user = $fjordUser;
            }
        }
        $this->fjordUsers = FjordUser::all();
    }
}
