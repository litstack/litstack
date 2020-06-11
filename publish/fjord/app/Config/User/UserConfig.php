<?php

namespace FjordApp\Config\User;

use Fjord\Crud\CrudShow;
use Fjord\Crud\CrudIndex;
use Illuminate\Support\Str;
use Fjord\Vue\Crud\CrudTable;
use Fjord\User\Models\FjordUser;
use Fjord\Crud\Config\CrudConfig;
use Illuminate\Support\Facades\Route;
use FjordApp\Controllers\User\UserController;

class UserConfig extends CrudConfig
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
    public $controller = UserController::class;

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
            'singular' => ucfirst(__f('fj.users')),
            'plural' => ucfirst(__f('fj.users')),
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

    /**
     * Crud show container.
     *
     * @param CrudShow $container
     * @return void
     */
    public function show(CrudShow $container)
    {
        $container->card(function ($form) {
            $form->input('first_name')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2')
                ->title(ucwords(__f('base.first_name')));

            $form->input('last_name')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2')
                ->title(ucwords(__f('base.last_name')));

            $form->input('email')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('email:rfc,dns', 'unique:fjord_users,email')
                ->title('E-Mail');

            $form->input('username')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2', 'max:60', 'unique:fjord_users,username')
                ->title(ucwords(__f('base.username')));

            $form->password('password')
                ->title(ucwords(__f('base.password')))
                ->width(1 / 2);
        });
    }
}
