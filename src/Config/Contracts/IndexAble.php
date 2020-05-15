<?php

namespace Fjord\Config\Contracts;

use Fjord\Vue\Table;
use Illuminate\Database\Eloquent\Builder;

interface IndexAble
{
    /**
     * Setup index table.
     *
     * @param \Fjord\Vue\Table $table
     * @return void
     */
    public function index(Table $table);

    /**
     * Initialize index query.
     *
     * @param Builder $query
     * @return Builder $query
     */
    public function indexQuery(Builder $query);

    /**
     * Sort by keys.
     *
     * @return array $sortBy
     */
    public function sortBy();

    /**
     * Index table filter groups.
     *
     * @return void
     */
    public function filter();
}
