<?php

namespace Fjord\Form\FormFields;

use Fjord\Form\FormFields\Traits\Relation as FormRelation;

class Relation
{
    use FormRelation;

    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'model',
        'preview',
        'title',
    ];

    const DEFAULTS = [
        'many' => false,
        'multiple_selection' => false,
        'readonly' => false,
    ];

    public static function prepare($field, $path)
    {
        $field = self::prepareRelation($field, $path);

        return $field;
    }
}
