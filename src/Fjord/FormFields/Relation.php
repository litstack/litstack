<?php

namespace AwStudio\Fjord\Fjord\FormFields;

use Illuminate\Support\Str;

class Relation
{
    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'model',
        'preview',
    ];

    const DEFAULTS = [
        'many' => false,
    ];

    public static function prepare($field, $path)
    {
        // Get query builder from model string.
        $field->query = $field->model;

        // foreign key
        
        // local key

        if(is_string($field->model)) {
            $field->query = $field->model::query();
        } else {
            $field->model = get_class($field->model->getModel());
        }

        if(! $field->attributeExists('button')) {
            $tableName = with(new $field->model)->getTable();
            $field->button = "Add " . ucfirst(Str::singular($tableName));
        }

        return $field;
    }
}
