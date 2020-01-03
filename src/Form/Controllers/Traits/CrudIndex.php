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
        // eager load models default withs
        $query = $this->model::with($this->getWiths());

        // apply the filter
        if($request->filter) {
            $scope = $request->filter;
            $query = $query->$scope();
        }

        // and the related models added in the crud->index->load
        $query->with(array_keys($request->eagerLoad));


        // perform search
        $query = $this->search($request, $query);


        // if sorted by eager loaded value, we need a query with ordering
        $query = $this->orderByEager($request, $query);


        // pagination
        $items = $this->paginate($request, $query);


        // sort items, if needed
        $items = $this->sort($request, $items);


        if(is_translatable($this->model)) {
            $items->map(function($item) {
                return $item->append('translation');
            });
        }

        return [
            'count' => $total ?? 0,
            'items' => $items
        ];
    }


    /**
     * Detect, wich Models need to be eagerloaded by default
     *
     * @return Array
     */
    protected function getWiths(): Array
    {
        $withs = [];

        if(has_media($this->model)) {
            $withs []= 'media';
        }
        if(is_translatable($this->model)) {
            $withs []= 'translations';
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
    private function sort(Request $request, Collection $items): Collection
    {
        // sort
        if(!$request->sort_by){
            return $items;
        }

        $key = explode('.', $request->sort_by)[0];
        $order = last(explode('.', $request->sort_by));

        // check, if is already eager ordered
        if(in_array($key, array_keys($request->eagerLoad))){
            return $items;
        }

        switch ( $order ) {
            case 'desc':
                $items = $items->sortByDesc($key, SORT_NATURAL|SORT_FLAG_CASE)->values();
                break;
            case 'asc':
            default:
                $items = $items->sortBy($key, SORT_NATURAL|SORT_FLAG_CASE)->values();
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
    private function orderByEager(Request $request, Builder $query): Builder
    {
        $key = $request->sort_by;
        $order = 'asc';

        if(strpos($key, '.') !== false) {
            $key = explode('.', $request->sort_by)[0];
            $order = last(explode('.', $request->sort_by));
        }


        if(in_array($key, array_keys($request->eagerLoad))){

            // get the table names of the related models
            $foreign_table = with(new $request->eagerLoad[$key])->getTable();
            $table = with(new $this->model)->getTable();

            // join the related table for ordering by a foreign column
            $query->leftJoin($foreign_table, $foreign_table . '.id', '=', $table . '.' . rtrim($foreign_table, 's') . '_id')
                  ->select($table . '.*', $foreign_table . '.' . explode('.', $request->sort_by)[1] . ' as eager_order_column' )
                  ->orderBy($foreign_table.'.'.explode('.', $request->sort_by)[1], $order);
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
        if($request->perPage !== 0){
            $page = $request->page ?? 1;
            $count = $this->model::all()->count();
            $perPage = $request->perPage;
            $total = $query->get()->count();

            $items = $query->skip( ($page-1) * $perPage )->take($perPage)->get();
        }else{
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

        if($request->search) {
            $query->whereLike($form->index['search'], $request->search);
        }

        return $query;
    }
}
