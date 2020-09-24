<?php

namespace Ignite\Crud;

use Ignite\Crud\Fields\Block\Repeatables;
use Illuminate\Support\Str;
use InvalidArgumentException;

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

    /**
     * Register the given repeatable.
     *
     * @param  string $repeatable
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function repeatable($name, $repeatable)
    {
        if (! is_subclass_of($repeatable, Repeatable::class)) {
            throw new InvalidArgumentException("The class [{$repeatable}] must be a subclass of ".Repeatable::class);
        }

        Repeatables::macro($name, function (...$parameters) use ($repeatable, $name) {
            $repeatable = new $repeatable(...$parameters);

            return $this->add($name, function ($form, $preview) use ($repeatable) {
                $repeatable->preview($preview);
                $repeatable->form($form);
            });
        });
    }
}
