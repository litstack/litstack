<?php

namespace Fjord\Crud\Controllers\Api;

use Fjord\Support\IndexTable;
use Fjord\Crud\Requests\CrudReadRequest;

trait CrudHasIndex
{
    /**
     * Load index table items.
     *
     * @param CrudReadRequest $request
     * @return array $items
     */
    public function indexTable(CrudReadRequest $request)
    {
        $table = $this->config->index->getTable();
        $query = $table->getQuery($this->query());

        $index = IndexTable::query($query)
            ->request($request)
            ->search($table->getAttribute('search'))
            ->get();

        $index['items'] = crud($index['items']);

        return $index;
    }
}
