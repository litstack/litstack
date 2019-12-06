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

        if($request->search) {
            $query->whereLike($form->index['search'], $request->search);
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
                    $query->join($foreign_table, $foreign_table . '.id', '=', $table . '.' . rtrim($foreign_table, 's') . '_id')
                          ->orderBy($foreign_table.'.'.explode('.', $request->sort_by)[1], $order)
                          ->get();

                }else{
                    $query->orderBy($key, $order);
                }
            }else{
                $query->orderBy($key, $order);
            }

        }

        $items = $query->get();



        if(is_translatable($this->model)) {
            $items->map(function($item) {
                return $item->append('translation');
            });
        }

        return $items;
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
