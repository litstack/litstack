<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\Collection;

class Form
{
    protected $data = [];

    public function load($collection = null, $name = null)
    {
        $query = Database\FormField::query();

        if($collection) {
            $query->where('collection', $collection);
        }

        if($name) {
            $query ->where('form_name', $name);
        }

        return new FormCollection($query->get());
    }
}
