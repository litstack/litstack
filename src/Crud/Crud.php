<?php

namespace Ignite\Crud;

use Illuminate\Support\Str;

/**
 * Crud singleton.
 *
 * @see \Ignite\Support\Facades\Crud
 */
class Crud
{
    /**
     * Get model names.
     *
     * @return array
     */
    public function names(string $model)
    {
        $modelInstance = new $model();

        if (method_exists($modelInstance, 'names')) {
            return $modelInstance->names();
        }

        return [
            'singular' => class_basename($model),
            'plural'   => Str::plural(class_basename($model)),
        ];
    }
}
