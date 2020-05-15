<?php

namespace Fjord\User\Config;

use Fjord\Vue\Table;
use Fjord\User\Models\FjordUser;
use Fjord\Config\Traits\HasIndex;
use Fjord\Config\Contracts\IndexAble;
use Fjord\User\Components\UsersComponent;
use Illuminate\Database\Eloquent\Builder;

abstract class IndexConfig implements IndexAble
{
    use HasIndex;

    /**
     * Fjord user model.
     *
     * @var string
     */
    public $model = FjordUser::class;

    /**
     * Index table search keys.
     *
     * @var array
     */
    public $search = ['name', 'email'];

    /**
     * Index table sort by default.
     *
     * @var string
     */
    public $sortByDefault = 'id.desc';

    /**
     * Items per page.
     *
     * @var integer
     */
    public $perPage = 20;

    /**
     * Build user index table.
     *
     * @param Table $table
     * @return void
     */
    abstract public function index(Table $table);

    /**
     * Initialize index query.
     *
     * @param Builder $query
     * @return Builder $query
     */
    public function indexQuery(Builder $query)
    {
        //
    }

    /**
     * Component
     *
     * @param UsersComponent $component
     * @return void
     */
    public function component(UsersComponent $component)
    {
        //
    }
}
