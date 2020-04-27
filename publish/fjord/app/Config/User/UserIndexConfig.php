<?php

namespace FjordApp\Config\User;

use Fjord\Vue\Table;
use Fjord\User\Models\FjordUser;
use Fjord\Config\Traits\HasIndex;
use Fjord\Config\Contracts\IndexAble;
use Illuminate\Database\Eloquent\Builder;

class UserIndexConfig implements IndexAble
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
     * Build user index table.
     *
     * @param Table $table
     * @return void
     */
    public function index(Table $table)
    {
        $table->col()
            ->value('name')
            ->label('Name');

        $table->col()
            ->value('email')
            ->label('E-Mail');
    }
}
