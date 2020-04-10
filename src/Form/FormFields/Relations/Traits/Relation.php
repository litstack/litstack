<?php

namespace Fjord\Form\FormFields\Relations\Traits;

use Exception;

trait Relation
{
    public static function verifyRelation($name, $relation, $class)
    {
        if (get_class($relation) != $class) {
            throw new Exception("id for Form Field \"belongsTo\" must be return a " . basename($class) . " relation, found " . basename(get_class($relation)) . " instead.");
        }
    }

    /**
     * Prepare many relationship fields.
     *
     * @param [type] $field
     * @param [type] $path
     * @return void
     */
    public static function prepareRelation($field, $path)
    {
        // Get query builder from model string.
        $field->query = $field->model;

        if (is_string($field->model)) {
            $field->query = $field->model::query();
        } else {
            $field->model = get_class($field->model->getModel());
        }

        $field->translatable = false;
        $field->route = with(new $field->model)->getTable();

        return $field;
    }
}
