<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\Collection;
use AwStudio\Fjord\Form\Database\FormField;

class Form
{
    public function load($collection = null, $name = null)
    {
        $query = Database\FormField::query();


        if($collection) {
            $query->where('collection', $collection);
        }

        if($name) {
            $query->where('form_name', $name);
        }
        $items = new FormFieldCollection($query->get());

        $items = $this->getGroups(
            $items,
            $collection ? false : true,
            $name ? false : true
        );

        return $items;
    }

    /**
     * Get groups for collection and form_name and
     * create nested Collection based on the groups.
     *
     * @param  Illuminate\Support\Collection $items Collection of AwStudio\Fjord\Form\Database\FormField
     * @param  bool $loading_collection
     * @param  bool $loading_name
     *
     * @return AwStudio\Fjord\Form\FormFieldCollection|AwStudio\Fjord\Form\Collection
     */
    protected function getGroups(Collection $items, bool $loading_collection, bool $loading_name)
    {
        if(!$loading_collection && !$loading_name) {
            return $items;
        }

        if($loading_collection) {
            $items = new FormFieldCollection($items->groupBy('collection'));

            foreach($items as $collection => $item) {
                $items[$collection] = new FormFieldCollection($item->groupBy('form_name'));
            }

            return $items;
        }

        if($loading_name) {
            return new FormFieldCollection($items->groupBy('form_name'));
        }

        return $items;
    }
}
