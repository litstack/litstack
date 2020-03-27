<?php

namespace AwStudio\Fjord\Fjord\Models\Traits;

use Illuminate\Http\Request;

trait HasIndex
{
    public function scopeIndex($query, Request $request)
    {
        // Filter
        $query = $this->applyFilterToIndex($query, $request);

        // Search
        $query = $this->applySearchToIndex($query, $request);

        // Order/Sort
        $query = $this->applyOrderToIndex($query, $request);

        // Pagination
        $query = $this->applyPaginationToIndex($query, $request);

        $total = $query->count();
        $items = $query->get();

        return [
            'count' => $total ?? 0,
            'items' => $items
        ];
    }

    protected function applyFilterToIndex($query, $request)
    {
        if(! $request->filter) {
            return $query;
        }

        // TODO: Apply multiple filters
        $scope = $request->filter;
        $query = $query->$scope();

        return $query;
    }

    protected function applySearchToIndex($query, $request)
    {
        if($request->search) {
            $query->whereLike(['email'], $request->search);
        }

        return $query;
    }

    protected function applyOrderToIndex($query, $request)
    {
        return $query;
    }

    protected function applyPaginationToIndex($query, $request)
    {
        return $query;
    }
}
