<?php

namespace AwStudio\Fjord\Form\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Facade\Ignition\QueryRecorder\Query;

trait CrudIndex
{
    public function postIndex(Request $request)
    {
        // Initialize query builder
        $query = $this->initIndexQuery();

        // Eager loads
        $query = $this->prepareIndexQuery($query);

        // Filter
        $query = $this->applyFilterToIndexQuery($query, $request);

        // Search
        $query = $this->applySearchToIndexQuery($query, $request);

        // Order/Sort
        $query = $this->applyOrderToIndexQuery($query, $request);

        // Pagination
        $query = $this->applyPaginationToIndexQuery($query, $request);

        // Fetch
        $total = $query->count();
        $items = $query->get();

        /*
        // TODO: Check why this was needed. Sorting after fetching???
        // sort items, if needed
        $items = $this->sortItems($request, $items, $eager);
        */

        if (is_translatable($this->model)) {
            $items->map(function ($item) {
                return $item->append('translation');
            });
        }

        return [
            'count' => $total ?? 0,
            'items' => $items
        ];
    }

    /**
     * Set the initial query builder.
     */
    protected function initIndexQuery()
    {
        if (!array_key_exists('query', $this->getForm()->index)) {
            return new $this->model;
        }

        return $this->getForm()->index['query'];
    }

    /**
     * Eager Loads.
     */
    protected function prepareIndexQuery($query)
    {
        // Eager load models default withs.
        $query = $query->with($this->getWiths());

        // Eager load
        $eagerClasses = $this->getEagerClasses();
        if ($eagerClasses) {
            $query = $query->with(array_keys($eagerClasses));
        }

        return $query;
    }

    /**
     * Get the relations that should be eager loaded.
     *
     * @return array
     */
    protected function getEagerClasses()
    {
        return array_key_exists('load', $this->getForm()->toArray()['index'])
            ? $this->getForm()->toArray()['index']['load']
            : [];
    }

    /**
     * Apply filter to the index query builder.
     */
    protected function applyFilterToIndexQuery($query, Request $request)
    {
        if (!$request->filter) {
            return $query;
        }

        // TODO: Apply multiple filters
        $scope = $request->filter;
        $query = $query->$scope();

        return $query;
    }

    /**
     * Apply search query to the index query builder.
     */
    protected function applySearchToIndexQuery($query, Request $request)
    {
        $form = $this->getForm();

        if ($request->search) {
            $query->whereLike($form->index['search'], $request->search);
        }

        return $query;
    }

    /**
     * Apply order to the index query builder.
     */
    protected function applyOrderToIndexQuery($query, Request $request)
    {
        if (!$request->sort_by) {
            return $query;
        }

        // Get order key and order direction
        $key = $request->sort_by;
        $order = 'asc';
        if (strpos($key, '.') !== false) {
            $key = explode('.', $request->sort_by)[0];
            $order = last(explode('.', $request->sort_by));
        }

        // Order for eager keys
        $eagerClasses = $this->getEagerClasses();
        $eager = array_keys($eagerClasses);
        if (in_array($key, $eager)) {

            // get the table names of the related models
            $foreign_table = with(new $eagerClasses[$key])->getTable();
            $table = with(new $this->model)->getTable();

            // join the related table for ordering by a foreign column
            $query->leftJoin($foreign_table, $foreign_table . '.id', '=', $table . '.' . rtrim($foreign_table, 's') . '_id')
                ->select($table . '.*', $foreign_table . '.' . explode('.', $request->sort_by)[1] . ' as eager_order_column')
                ->orderBy($foreign_table . '.' . explode('.', $request->sort_by)[1], $order);
        } else {

            // Order
            $query->orderBy($key, $order);
        }

        return $query;
    }

    /**
     * Apply pagination limits to the index query builder.
     */
    protected function applyPaginationToIndexQuery($query, Request $request)
    {
        if ($request->perPage !== 0) {
            $page = $request->page ?? 1;
            $perPage = $request->perPage;
            $query->skip(($page - 1) * $perPage)->take($perPage);
        }

        return $query;
    }


    /**
     * Detect, wich Models need to be eagerloaded by default
     *
     * @return Array
     */
    protected function getWiths(): array
    {
        $withs = ['last_edit'];

        if (has_media($this->model)) {
            $withs[] = 'media';
        }
        if (is_translatable($this->model)) {
            $withs[] = 'translations';
        }

        return $withs;
    }


    /**
     * Sorting is done via collection to provide accessor sorting
     *
     * @param  Request
     * @param  Collection
     * @return Collection
     */
    private function sortItems(Request $request, Collection $items, $eager): Collection
    {
        // sort
        if (!$request->sort_by) {
            return $items;
        }

        $key = explode('.', $request->sort_by)[0];
        $order = last(explode('.', $request->sort_by));

        // check, if is already eager ordered
        if (in_array($key, $eager)) {
            return $items;
        }

        switch ($order) {
            case 'desc':
                $items = $items->sortByDesc($key, SORT_NATURAL | SORT_FLAG_CASE)->values();
                break;
            case 'asc':
            default:
                $items = $items->sortBy($key, SORT_NATURAL | SORT_FLAG_CASE)->values();
                break;
        }

        return $items;
    }



    /**
     * Sort by the eager loaded tables column
     *
     * @param  Request
     * @param  Builder
     * @return Builder
     */
    private function orderByEager(Request $request, Builder $query, $eagerClasses): Builder
    {
        $key = $request->sort_by;
        $order = 'asc';

        if (strpos($key, '.') !== false) {
            $key = explode('.', $request->sort_by)[0];
            $order = last(explode('.', $request->sort_by));
        }

        $eager = array_keys($eagerClasses);

        if (in_array($key, $eager)) {
            // get the table names of the related models

            $foreign_table = with(new $eagerClasses[$key])->getTable();
            $table = with(new $this->model)->getTable();

            // join the related table for ordering by a foreign column
            $query->leftJoin($foreign_table, $foreign_table . '.id', '=', $table . '.' . rtrim($foreign_table, 's') . '_id')
                ->select($table . '.*', $foreign_table . '.' . explode('.', $request->sort_by)[1] . ' as eager_order_column')
                ->orderBy($foreign_table . '.' . explode('.', $request->sort_by)[1], $order);
        }

        return $query;
    }


    /**
     * Apply pagination to the request if needed
     *
     * @param  Request
     * @param  Collection
     * @return Collection
     */
    private function paginate(Request $request, Builder $query): Collection
    {
        // if a perPage value is set, get the requested page,
        // else get all items

        if ($request->perPage !== 0) {
            $page = $request->page ?? 1;
            $perPage = $request->perPage;
            $items = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        } else {
            $items = $query->get();
        }

        return $items;
    }


    /**
     * Perform a search if needed
     *
     * @param  Request
     * @param  Builder
     * @return Builder
     */
    private function search(Request $request, Builder $query): Builder
    {
        $form = $this->getForm();

        if ($request->search) {
            $query->whereLike($form->index['search'], $request->search);
        }

        return $query;
    }
}
