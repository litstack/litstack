<?php

namespace Fjord\Form\FormFields;

use Illuminate\Support\Str;
use Fjord\Form\FormFields\Traits\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo as RelationBelongsTo;

class BelongsTo
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

        $model = $field->getModel();
        $relation = with(new $model)->{$field->id}();

        self::verifyRelation("belongsTo", $relation, RelationBelongsTo::class);

        $field->setAttribute('foreign_key', $relation->getForeignKeyName());
        $field->setAttribute('local_key', $field->foreign_key);

        return $field;
    }
}
