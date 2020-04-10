<?php

namespace Fjord\Form\FormFields\Relations;

use Illuminate\Support\Str;
use Fjord\Form\FormFields\Relations\Traits\Relation;

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
        'sortable' => false,
        'confirm_unlink' => false,
    ];

    public static function prepare($field, $path)
    {
        $field = self::prepareRelation($field, $path);

        return $field;
    }
}
