<?php

namespace Fjord\Form\FormFields\Traits;

use Exception;

trait MorphToRelation
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
        $queries = [];
        $models = [];
        $routes = [];
        foreach ($field->models as $model) {
            $models[] = $model;
            $queries[$model] = $model;

            if (is_string($model)) {
                $queries[$model] = $model::query();
            } else {
                $models[] = get_class($model->getModel());
            }

            $routes[$model] = with(new $model)->getTable();
        }
        $field->queries = $queries;
        $field->models = $models;
        $field->routes = $routes;

        $field->translatable = false;

        return $field;
    }
}
