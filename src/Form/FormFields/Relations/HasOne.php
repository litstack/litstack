<?php

namespace Fjord\Form\FormFields\Relations;

use Fjord\Form\FormFields\Relations\Traits\Relation;
use Illuminate\Database\Eloquent\Relations\HasOne as RelationHasOne;

class HasOne
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

        self::verifyRelation("hasOne", $relation, RelationHasOne::class);

        $field->setAttribute('foreign_key', $relation->getForeignKeyName());
        $field->setAttribute('local_key', $field->foreign_key);

        return $field;
    }
}
