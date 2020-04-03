<?php

namespace AwStudio\Fjord\Support;

use Illuminate\Http\Request;

class IndexTable
{
    protected $query;

    protected $request;

    protected $only = [];

    protected $except = [];

    public function __construct($query, Request $request)
    {
        $this->query = $query;
        $this->request = $request;
    }

    public static function deleteSelected($class, Request $request)
    {
        $class::whereIn('id', $request->ids)->delete();
    }

    public function except(array $except)
    {
        $this->except = $except;

        return $this;
    }

    public function only(array $only)
    {
        $this->only = $only;

        return $this;
    }

    public function items()
    {
        $actions = ['filter', 'search', 'order', 'paginate'];
        if (!empty($this->only)) {
            $actions = $this->only;
        } else if (!empty($this->except)) {
            foreach ($this->except as $action) {
                if (($key = array_search($action, $actions)) !== false) {
                    unset($actions[$key]);
                }
            }
        }

        if (in_array('filter', $actions)) {
            self::applyFilterToIndex();
        }

        if (in_array('search', $actions)) {
            self::applySearchToIndex();
        }

        if (in_array('order', $actions)) {
            self::applyOrderToIndex();
        }

        if (in_array('paginate', $actions)) {
            self::applyPaginationToIndex();
        }

        $total = $this->query->count();
        $items = $this->query->get();

        return [
            'count' => $total ?? 0,
            'items' => $items
        ];
    }

    public static function get($query, Request $request)
    {
        return with(new self($query, $request))->items();
    }

    protected function applyFilterToIndex()
    {
        if (!$this->request->filter) {
            return;
        }

        // TODO: Apply multiple filters
        $scope = $this->request->filter;
        $this->query = $this->query->$scope();
    }

    protected function applySearchToIndex()
    {
        if ($this->request->search) {
            $this->query->whereLike($this->request->searchKeys, $this->request->search);
        }
    }

    protected function applyOrderToIndex()
    {
        if (!$this->request->sort_by) {
            return;
        }

        // Get order key and order direction
        $key = $this->request->sort_by;
        $order = 'asc';
        if (strpos($key, '.') !== false) {
            $key = explode('.', $this->request->sort_by)[0];
            $order = last(explode('.', $this->request->sort_by));
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
        $this->query->orderBy($key, $order);
        /*
        }
        */
    }

    protected function applyPaginationToIndex()
    {
        if ($this->request->perPage !== 0) {
            $page = $this->request->page ?? 1;
            $perPage = $this->request->perPage;
            $this->query->skip(($page - 1) * $perPage)->take($perPage);
        }
    }
}
