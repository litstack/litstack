<?php

namespace Fjord\Support;

use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Builder;

class IndexTable
{
    /**
     * Query builder
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Request
     *
     * @var Request
     */
    protected $request;

    /**
     * Only.
     *
     * @var array
     */
    protected $only = [];

    /**
     * Except.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Search keys.
     *
     * @var array
     */
    protected $searchKeys = [];

    /**
     * Create new IndexTable instance.
     *
     * @param Builder $query
     * @param Request|null $request
     * @return void
     */
    public function __construct($query, $request = null)
    {
        $this->query = $query;
        $this->request = $request;
    }

    /**
     * Create new IndexTable with query.
     *
     * @param Builder $query
     * @return void
     */
    public static function query($query)
    {
        return new self($query);
    }

    /**
     * Set request.
     *
     * @param Request $request
     * @return self
     */
    public function request(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set search keys.
     *
     * @param array $keys
     * @return self
     */
    public function search(array $keys)
    {
        $this->searchKeys = $keys;

        return $this;
    }

    /**
     * Delete selected items.
     *
     * @param string $class
     * @param Request $request
     * @return void
     */
    public static function deleteSelected($class, Request $request)
    {
        $class::whereIn('id', $request->ids)->delete();
    }

    /**
     * Except.
     *
     * @param array $except
     * @return void
     */
    public function except(array $except)
    {
        $this->except = $except;

        return $this;
    }

    /**
     * Only.
     *
     * @param array $only
     * @return void
     */
    public function only(array $only)
    {
        $this->only = $only;

        return $this;
    }

    /**
     * Fetch items.
     *
     * @return array
     * 
     * @throws \InvalidArgumentException
     */
    public function get()
    {
        if (!$this->request) {
            throw new InvalidArgumentException('Missing argument request for IndexTable.');
        }

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
            self::applyFilterToQuery();
        }

        if (in_array('search', $actions)) {
            self::applySearchToQuery();
        }

        if (in_array('order', $actions)) {
            self::applyOrderToQuery();
        }

        $itemsQuery = clone $this->query;

        if (in_array('paginate', $actions)) {
            $itemsQuery = self::applyPaginationToQuery($itemsQuery);
        }

        $total = $this->query->count();

        $items = $itemsQuery->get();

        return [
            'count' => $total ?? 0,
            'items' => $items
        ];
    }

    /**
     * Apply filter to query.
     *
     * @return void
     */
    protected function applyFilterToQuery()
    {
        if (!$this->request->filter) {
            return;
        }

        $eagerLoads = $this->query->getEagerLoads();

        // TODO: Apply multiple filters
        $scope = $this->request->filter;
        $this->query = $this->query->$scope();

        // Eager loads are not passed to the scope. Therefore they are reset 
        // after the scope is executed.
        $this->query->setEagerLoads($eagerLoads);
    }

    /**
     * Apply search to query.
     *
     * @return void
     */
    protected function applySearchToQuery()
    {
        if (!$this->request->search) {
            return;
        }

        $this->query->whereLike(
            $this->searchKeys,
            $this->request->search
        );
    }

    /**
     * Apply order to query.
     *
     * @return void
     */
    protected function applyOrderToQuery()
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

        $model = $this->query->getModel();

        if (array_key_exists($key, $this->query->getEagerLoads())) {
            return $this->query->orderByRelation($key, explode('.', $this->request->sort_by)[1], $order);
        }

        if (!is_translatable($model)) {
            return $this->query->orderBy($key, $order);
        }

        if (!in_array($key, $model->translatedAttributes)) {
            return $this->query->orderBy($key, $order);
        }

        return $this->query->orderByTranslation(app()->getLocale(), $key, $order);
    }

    /**
     * Apply pagination to query. An instance of the Builder is passed here 
     * because this part should not be applied to the count query.
     *
     * @param Builder $query
     * @return Buider
     */
    protected function applyPaginationToQuery($query)
    {
        if ($this->request->perPage === 0) {
            return $query;
        }

        $page = $this->request->page ?? 1;
        $perPage = $this->request->perPage ?? 20;
        $query->skip(($page - 1) * $perPage)->take($perPage);

        return $query;
    }
}
