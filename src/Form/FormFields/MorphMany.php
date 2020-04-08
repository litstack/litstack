<?php

namespace Fjord\Form\FormFields;

use Fjord\Form\FormFields\Traits\Relation;

class MorphMany
{
    use Relation;

    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'model',
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

        $field->setAttribute('morph_type', $relation->getMorphType());
        $field->setAttribute('morph_type_value', $model);
        $field->setAttribute('foreign_key', $relation->getForeignKeyName());

        return $field;
    }
}
