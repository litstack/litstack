<?php

namespace Fjord\Form\FormFields\Relations;

use Illuminate\Support\Str;
use Fjord\Form\FormFields\Relations\Traits\Relation;
use Illuminate\Database\Eloquent\Relations\MorphOne as RelationMorphOne;


class MorphOne
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
        'readonly' => true,
    ];

    public static function prepare($field, $path)
    {
        $field = self::prepareRelation($field, $path);

        $model = $field->getModel();
        $relation = with(new $model)->{$field->id}();

        self::verifyRelation("morphOne", $relation, RelationMorphOne::class);

        $field->setAttribute('morph_type', $relation->getMorphType());
        $field->setAttribute('morph_type_value', $model);
        $field->setAttribute('foreign_key', $relation->getForeignKeyName());
        $field->setAttribute('local_key_name', $relation->getLocalKeyName());

        return $field;
    }
}
