<?php

namespace Lit\Config\User;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudIndex;
use Ignite\Crud\CrudShow;
use Ignite\Page\Table\ColumnBuilder;
use Illuminate\Support\Facades\Route;
use Lit\Http\Controllers\User\UserController;
use Lit\Models\User;

class UserConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = User::class;

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
            'singular' => ucfirst(__lit('base.user')),
            'plural'   => ucfirst(__lit('base.users')),
        ];
    }

    /**
     * Build user index table.
     *
     * @param  CrudIndex $page
     * @return void
     */
    public function index(CrudIndex $page)
    {
        $page->table(fn ($table) => $this->indexTable($table))
            ->query(fn ($query) => $query->with('ordered_roles'))
            ->sortByDefault('id.desc')
            ->search('username', 'first_name', 'last_name', 'email');
    }

    /**
     * User index table.
     *
     * @param  ColumnBuilder $table
     * @return void
     */
    public function indexTable(ColumnBuilder $table)
    {
        $table->col()
            ->value('{first_name} {last_name}')
            ->label('Name');

        $table->col()
            ->value('email')
            ->label('E-Mail');

        $table->component('lit-permissions-lit-users-roles')
            ->link(false)
            ->label(ucfirst(__lit('base.roles')));

        $table->component('lit-permissions-lit-users-apply-role')
            ->authorize(fn ($user) => $user->can('update lit-user-roles'))
            ->label('')
            ->link(false)
            ->small();
    }

    /**
     * Crud show container.
     *
     * @param  CrudShow $page
     * @return void
     */
    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('first_name')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2', 'max:255')
                ->title(ucwords(__lit('base.first_name')));

            $form->input('last_name')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2', 'max:255')
                ->title(ucwords(__lit('base.last_name')));

            $form->input('email')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('email:rfc,dns', 'unique:lit_users,email')
                ->title('E-Mail');

            $form->input('username')
                ->width(1 / 2)
                ->creationRules('required')
                ->rules('min:2', 'max:60', 'unique:lit_users,username')
                ->title(ucwords(__lit('base.username')));

            $form->password('password')
                ->title(ucwords(__lit('base.password')))
                ->width(1 / 2);
        });
    }
}
