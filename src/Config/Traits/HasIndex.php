<?php

namespace Fjord\Config\Traits;

use Fjord\Vue\Table;
use Illuminate\Database\Eloquent\Builder;

trait HasIndex
{
    /**
     * Index table search keys.
     *
     * @var array
     */
    public $search = ['title'];

    /**
     * Index table sort by default.
     *
     * @var string
     */
    public $sortByDefault = 'id.desc';

    /**
     * Setup index table.
     *
     * @param \Fjord\Vue\Table $table
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
        return $query;
    }

    /**
     * Sort by keys.
     *
     * @return array $sortBy
     */
    public function sortBy()
    {
        return [
            'id.desc' => 'New first',
            'id.asc' => 'Old first',
        ];
    }

    /**
     * Index table filter groups.
     *
     * @return void
     */
    public function filter()
    {
        return [];
    }
}
