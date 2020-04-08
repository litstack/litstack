<?php

namespace Fjord\Form\FormFields;

use Illuminate\Support\Str;
use Fjord\Form\FormFields\Traits\Relation;

class BelongsToMany
{
    use Relation;

    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'model',
        'id',
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
