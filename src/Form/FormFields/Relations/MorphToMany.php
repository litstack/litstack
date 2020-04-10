<?php

namespace Fjord\Form\FormFields\Relations;

use Fjord\Form\FormFields\Relations\Traits\Relation;

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
        'sortable' => false,
        'confirm_unlink' => false,
    ];

    public static function prepare($field, $path)
    {
        $field = self::prepareRelation($field, $path);
        return $field;
    }
}
