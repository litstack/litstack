<?php

if(! function_exists('fjord_resource_path')) {
    function fjord_resource_path($path = '') {
        return resource_path(config('fjord.resource_path') . ($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}

if(! function_exists('fjord_path')) {
    function fjord_path($path = '') {
        return realpath(__DIR__ . '/../../').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if(! function_exists('fjord')) {
    function fjord() {
        return app()->get('fjord');
    }
}

if(! function_exists('form_field_collect')) {
    function form_field_collect(array $array) {
        return new \AwStudio\Fjord\Form\FormFieldCollection($array);
    }
}

if(! function_exists('eloquentJs')) {
    function eloquentJs($model, $class, $type = 'fjord') {
        return (new AwStudio\Fjord\EloquentJs\EloquentJs($model, $class, $type))->toArray();
    }
}

if(! function_exists('is_translateable')) {
    function is_translateable($model)
    {
        $reflect = new \ReflectionClass($model);
        if ($reflect->implementsInterface('Astrotomic\Translatable\Contracts\Translatable')){
            return true;
        }
        return false;
    }
}

if(! function_exists('closure_info')) {
    function closure_info(callable $closure)
    {
        return new \ReflectionFunction($closure);
    }
}

if(! function_exists('is_valid_path')) {
    function is_valid_path($path)
    {
        return (bool) file_exists($path);
    }
}
