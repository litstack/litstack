<?php

namespace AwStudio\Fjord\Form\Controllers\Traits;

use Illuminate\Http\Request;

trait CrudIndex
{
    public function postIndex(Request $request)
    {
        $query = $this->model::with($this->getWiths());
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
            }

            $query->orderBy($key, $order);
        }

        $items = $query->get();

        if(is_translatable($this->model)) {
            $items->map(function($item) {
                return $item->append('translation');
            });
        }

        return $items;
    }

    protected function getWiths()
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
