<?php

namespace Fjord\Form\FormFields\Relations;

use Illuminate\Support\Str;
use Fjord\Form\FormFields\Relations\Traits\MorphToRelation;
use Illuminate\Database\Eloquent\Relations\MorphTo as RelationMorphTo;


class MorphTo
{
    use MorphToRelation;

    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'preview',
        'title',
        'models',
    ];

    const DEFAULTS = [
        'readonly' => false,
    ];

    public static function prepare($field, $path)
    {
        $field = self::prepareRelation($field, $path);

        $model = $field->getModel();
        $relation = with(new $model)->{$field->id}();

        self::verifyRelation("morphTo", $relation, RelationMorphTo::class);

        $field->setAttribute('morph_type', $relation->getMorphType());
        $field->setAttribute('foreign_key', $relation->getForeignKeyName());

        return $field;
    }
}
