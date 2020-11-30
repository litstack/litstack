<?php

namespace Ignite\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class IndexTable
{
    /**
     * Query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Request.
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
     * Model casts.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Create new IndexTable instance.
     *
     * @param  Builder      $query
     * @param  Request|null $request
     * @return void
     */
    public function __construct($query, Request $request = null)
    {
        $this->query = $query;
        $this->request = $request;
    }

    /**
     * Create new IndexTable with query.
     *
     * @param  Builder $query
     * @return void
     */
    public static function query($query)
    {
        return new self($query);
    }

    /**
     * Set request.
     *
     * @param  Request $request
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
     * @param  array $keys
     * @return $this
     */
    public function search(array $keys)
    {
        $this->searchKeys = $keys;

        return $this;
    }

    /**
     * Set casts for index table.
     *
     * @param  array $casts
     * @return $this
     */
    public function casts(array $casts)
    {
        $this->casts = $casts;

        return $this;
    }

    /**
     * Get search keys.
     *
     * @return array
     */
    public function getSearchKeys()
    {
        return $this->searchKeys;
    }

    /**
     * Delete selected items.
     *
     * @param  string  $class
     * @param  Request $request
     * @return void
     */
    public static function deleteSelected($class, Request $request)
    {
        $class::whereIn('id', $request->ids)->delete();
    }

    /**
     * Except.
     *
     * @param  array $except
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
     * @param  array $only
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
     * @throws \InvalidArgumentException
     * @return array
     */
    public function get()
    {
        if (! $this->request) {
            throw new InvalidArgumentException('Missing argument request for IndexTable.');
        }

        $actions = ['filter', 'search', 'order', 'paginate'];
        if (! empty($this->only)) {
            $actions = $this->only;
        } elseif (! empty($this->except)) {
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
            self::applySortToQuery();
        }

        $itemsQuery = clone $this->query;

        if (in_array('paginate', $actions)) {
            $itemsQuery = self::applyPaginationToQuery($itemsQuery);
        }

        $total = $this->query->count();

        $items = $itemsQuery->get()->each->mergeCasts($this->casts);

        return [
            'count' => $total ?? 0,
            'items' => $items,
        ];
    }

    /**
     * Apply filter to query.
     *
     * @return void
     */
    protected function applyFilterToQuery()
    {
        if (! $this->request->filter) {
            return;
        }

        foreach (Arr::wrap($this->request->filter) as $scope) {
            if (! is_array($scope)) {
                $this->query = $this->query->$scope();
            } else {
                $this->applyFilterClass($scope['filter'], $scope['values']);
            }
        }
    }

    /**
     * Apply filter class.
     *
     * @param  string $namespace
     * @param  array  $values
     * @return void
     */
    protected function applyFilterClass($namespace, array $values)
    {
        $filter = (new $namespace);

        $filter->apply($this->query, new AttributeBag($values));
    }

    /**
     * Apply search to query.
     *
     * @return void
     */
    protected function applySearchToQuery()
    {
        if (! $this->request->search) {
            return;
        }

        $this->query->search(
            $this->searchKeys,
            $this->request->search
        );
    }

    /**
     * Apply sort to query.
     *
     * @return void
     */
    protected function applySortToQuery()
    {
        if (! $this->request->sort_by) {
            return;
        }

        // Get sort key and direction.
        [$key, $direction] = $this->parseSortKey($this->request->sort_by);

        return $this->query->sort($key, $direction);
    }

    /**
     * Parse sort key.
     *
     * @param  string $key
     * @return array
     */
    public function parseSortKey(string $key)
    {
        $direction = 'asc';

        if (Str::endsWith($key, '.asc') || Str::endsWith($key, 'desc')) {
            $direction = last(explode('.', $key));
            $key = str_replace(".{$direction}", '', $key);
        }

        return [$key, $direction];
    }

    /**
     * Apply pagination to query. An instance of the Builder is passed here
     * because this part should not be applied to the count query.
     *
     * @param  Builder $query
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
