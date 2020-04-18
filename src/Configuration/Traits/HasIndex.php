<?php

namespace Fjord\Crud\Config\Traits;

use Fjord\Vue\Crud\CrudTable;

trait HasIndex
{
    /**
     * Index table search keys.
     *
     * @var array
     */
    protected $search = ['title'];

    /**
     * Index table sort by default.
     *
     * @var string
     */
    protected $sortByDefault = 'id.desc';

    /**
     * Setup index table.
     *
     * @param \Fjord\Vue\Crud\CrudTable $table
     * @return void
     */
    abstract protected function index(CrudTable $table);

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
