<?php

namespace Fjord\Form\FormFields;

use Fjord\Form\FormFields\Traits\Relation;

class MorphToMany
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

        return $field;
    }
}
