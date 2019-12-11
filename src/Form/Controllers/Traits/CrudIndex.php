<?php

namespace AwStudio\Fjord\Form\Controllers\Traits;

use Illuminate\Http\Request;

trait CrudIndex
{
    public function postIndex(Request $request)
    {
        // eager load models default withs
        $query = $this->model::with($this->getWiths());

        // and the related models added in the crud->index->load
        $query->with(array_keys($request->eagerLoad));

        $form = $this->getForm();

        // apply the search
        if($request->search) {
            $query->whereLike($form->index['search'], $request->search);
        }

        // apply the filter
        if($request->filter) {
            $scope = $request->filter;
            $query->$scope();
        }

        if($request->sort_by) {

            $key = $request->sort_by;
            $order = 'asc';

            if(strpos($key, '.') !== false) {
                $key = explode('.', $request->sort_by)[0];
                $order = last(explode('.', $request->sort_by));

                // check if is eager
                if(in_array($key, array_keys($request->eagerLoad))){
                    // get the table names of the related models
                    $foreign_table = with(new $request->eagerLoad[$key])->getTable();
                    $table = with(new $this->model)->getTable();

                    // join the related table for ordering by a foreign column
                    $query->rightJoin($foreign_table, $foreign_table . '.id', '=', $table . '.' . rtrim($foreign_table, 's') . '_id')
                          ->select($table . '.*', $foreign_table . '.' . explode('.', $request->sort_by)[1])
                          ->orderBy($foreign_table.'.'.explode('.', $request->sort_by)[1], $order)
                          ->get();

                }else{
                    $query->orderBy($key, $order);
                }
            }else{
                $query->orderBy($key, $order);
            }

        }

        //$items = $query->get();

        // pagination
        if($request->perPage !== 0){
            $page = $request->page ?? 1;
            $count = $this->model::all()->count();
            $perPage = $request->perPage;
            $total = $query->get()->count();

            $items = $query->skip( ($page-1) * $perPage )->take($perPage)->get();
        }else{
            $items = $query->get();
        }




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
}
