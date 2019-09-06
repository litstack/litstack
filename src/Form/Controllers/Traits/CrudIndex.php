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

        $items = $query->get();

        if(is_translatable($this->model)) {
            $items->map(function($item) {
                return $item->append('translation');
            });
        }

        return $items;
    }
}
