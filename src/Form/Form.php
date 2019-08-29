<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\Collection;

class Form
{
    public function load($collection = null, $name = null)
    {
        $query = Database\FormField::query();

        if($collection) {
            $query->where('collection', $collection);
        }

        if($name) {
            $query ->where('form_name', $name);
        }

        $items = new FormCollection($query->get());

        $items = $this->getGroups($items, $collection ? false : true, $name ? false : true);

        return $items;
    }

    public function getGroups($items, $collections, $names)
    {
        if(!$collections && !$names) {
            return $items;
        }

        if($collections) {
            $items = new FormCollection($items->groupBy('collection'));

            foreach($items as $collection => $item) {
                $items[$collection] = new FormCollection($item->groupBy('form_name'));
            }

            return $items;
        }

        if($names) {
            return new FormCollection($items->groupBy('form_name'));
        }
        
        return $items;
    }
}
