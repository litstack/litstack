<?php

namespace Lit\Config\Traits;

use Lit\Vue\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property-read array $search
 * @property-read string $sortByDefault
 * @property-read int $perPage
 */
trait HasIndex
{
    /**
     * Setup index table.
     *
     * @param \Lit\Vue\Table $table
     *
     * @return void
     */
    abstract public function index(Table $table);

    /**
     * Initialize index query.
     *
     * @param Builder $query
     *
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
            'id.desc' => __f('lit.sort_new_to_old'),
            'id.asc'  => __f('lit.sort_old_to_new'),
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
