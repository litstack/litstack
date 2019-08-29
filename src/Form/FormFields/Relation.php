<?php

namespace AwStudio\Fjord\Form\FormFields;

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

        if(is_string($field->model)) {
            $field->query = $field->model::query();
        } else {
            $field->model = get_class($field->model->getModel());
        }

        if(! $field->attributeExists('button')) {
            $tableName = with(new $field->model)->getTable();
            $field->button = "Add " . ucfirst(Str::singular($tableName));
        }

        $field->translatable = false;

        //$field = self::setKeys($field);

        return $field;
    }

    /*
    protected static function setKeys($field)
    {
        if($field->many) {
            return;
        }

        if(! $field->attributeExists('local_key')) {
            $field->local_key = "{$field->id}_id";
        }

        if(! $field->attributeExists('foreign_key')) {
            $field->foreign_key = "id";
        }

        return $field;
    }
    */
}
