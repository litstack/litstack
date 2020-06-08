<?php

namespace FjordApp\Config\User;

use Fjord\Crud\CrudIndex;
use Fjord\Vue\Crud\CrudTable;
use Fjord\User\Models\FjordUser;
use Fjord\Crud\Config\CrudConfig;
use FjordApp\Controllers\User\UserIndexController;

class UserIndexConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = FjordUser::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = UserIndexController::class;

    /**
     * Route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'users';
    }

    /**
     * Crud singular & plural name.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => ucfirst(__f('models.users')),
            'plural' => ucfirst(__f('models.users')),
        ];
    }

    /**
     * Build user index table.
     *
     * @param CrudIndex $table
     * @return void
     */
    public function index(CrudIndex $container)
    {
        $container->table(fn ($table) => $this->indexTable($table))
            ->query(fn ($query) => $query->with('ordered_roles'))
            ->sortByDefault('id.desc')
            ->search('username', 'first_name', 'last_name', 'email');
    }

    /**
     * User index table.
     *
     * @param \Fjord\Vue\Crud\CrudTable $table
     * @return void
     */
    public function indexTable(CrudTable $table)
    {
        $table->col()
            ->value('{first_name} {last_name}')
            ->label('Name');

        $table->col()
            ->value('email')
            ->label('E-Mail');

        $table->component('fj-permissions-fjord-users-roles')
            ->link(false)
            ->label(__f('fj.roles'));

        $table->component('fj-permissions-fjord-users-apply-role')
            ->authorize(fn ($user) => $user->can('update fjord-user-roles'))
            ->label('')
            ->link(false)
            ->small();
    }
}
