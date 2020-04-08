<?php

namespace Fjord\Form\FormFields;

use Fjord\Form\FormFields\Traits\Relation;

class HasMany
{
    use Relation;

    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'model',
        //'foreign_key',
        'preview',
        'title',
    ];

    const DEFAULTS = [
        'readonly' => false,
    ];

    public static function prepare($field, $path)
    {
        $field = self::prepareRelation($field, $path);

        $model = $field->getModel();
        $relation = with(new $model)->{$field->id}();

        $field->setAttribute('foreign_key', $relation->getForeignKeyName());

        return $field;
    }
}
