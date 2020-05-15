<?php

namespace FjordApp\Config\User;

use Fjord\Vue\Table;
use Fjord\User\Config\IndexConfig;

class UserIndexConfig extends IndexConfig
{
    /**
     * Index table search keys.
     *
     * @var array
     */
    public $search = ['username', 'first_name', 'last_name', 'email'];

    /**
     * Index table sort by default.
     *
     * @var string
     */
    public $sortByDefault = 'id.desc';

    /**
     * Build user index table.
     *
     * @param Table $table
     * @return void
     */
    public function index(Table $table)
    {
        $table->col()
            ->value('{first_name} {last_name}')
            ->label('Name');

        $table->col()
            ->value('email')
            ->label('E-Mail');
    }
}
