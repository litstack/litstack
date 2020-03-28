<?php

namespace AwStudio\Fjord\Fjord\Application;

use Illuminate\Http\Request;

class IndexTable
{
    public static function deleteSelected($class, Request $request)
    {
        $class::whereIn('id', $request->ids)->delete();
    }

    public static function get($query, Request $request)
    {
        // Filter
        $query = self::applyFilterToIndex($query, $request);

        // Search
        $query = self::applySearchToIndex($query, $request);

        // Order/Sort
        $query = self::applyOrderToIndex($query, $request);

        // Pagination
        $query = self::applyPaginationToIndex($query, $request);

        $total = $query->count();
        $items = $query->get();

        return [
            'count' => $total ?? 0,
            'items' => $items
        ];
    }

    protected static function applyFilterToIndex($query, $request)
    {
        if(! $request->filter) {
            return $query;
        }

        // TODO: Apply multiple filters
        $scope = $request->filter;
        $query = $query->$scope();

        return $query;
    }

    protected static function applySearchToIndex($query, $request)
    {
        if($request->search) {
            $query->whereLike($request->searchKeys, $request->search);
        }

        return $query;
    }

    protected static function applyOrderToIndex($query, $request)
    {
        if(!$request->sort_by){
            return $query;
        }

        // Get order key and order direction
        $key = $request->sort_by;
        $order = 'asc';
        if(strpos($key, '.') !== false) {
            $key = explode('.', $request->sort_by)[0];
            $order = last(explode('.', $request->sort_by));
        }

        // TODO: Sortable for eager
        // Order for eager keys
        /*
        $eagerClasses = $this->getEagerClasses();
        $eager = array_keys($eagerClasses);
        if(in_array($key, $eager)){

            // get the table names of the related models
            $foreign_table = with(new $eagerClasses[$key])->getTable();
            $table = with(new $this->model)->getTable();

            // join the related table for ordering by a foreign column
            $query->leftJoin($foreign_table, $foreign_table . '.id', '=', $table . '.' . rtrim($foreign_table, 's') . '_id')
                  ->select($table . '.*', $foreign_table . '.' . explode('.', $request->sort_by)[1] . ' as eager_order_column' )
                  ->orderBy($foreign_table.'.'.explode('.', $request->sort_by)[1], $order);

        } else {
        */
            // Order
            $query->orderBy($key, $order);
        /*
        }
        */

        return $query;
    }

    protected static function applyPaginationToIndex($query, $request)
    {
        if($request->perPage !== 0){
            $page = $request->page ?? 1;
            $perPage = $request->perPage;
            $query->skip( ($page-1) * $perPage )->take($perPage);
        }

        return $query;
    }

}
