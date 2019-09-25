<?php

namespace AwStudio\Fjord\Form\FormFields;

use Illuminate\Support\Str;

class MorphOne
{
    const TRANSLATABLE = false;

    const REQUIRED = [
        'type',
        'id',
        'models',
        'preview',
        'title',
        'morph'
    ];

    const DEFAULTS = [
    ];

    public static function prepare($field, $path)
    {
        //dd(get_class($field->models['Artikel']->getModel()));
        $field->querys = collect($field->models)->map(function($model) use ($field){
            //return $model::query();
            if(is_string($model)) {
                return $model::query();
            } else {
                $model = get_class($model->getModel());
                return $model::query();
            }
        })->toArray();

        return $field;
    }

}
