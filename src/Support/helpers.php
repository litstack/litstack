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

if(! function_exists('nested_collect')) {
    function nested_collect(array $array) {
        return new \AwStudio\Fjord\Support\NestedCollection($array);
    }
}

if(! function_exists('eloquentJs')) {
    function eloquentJs($model, $class, $type = 'fjord') {
        return (new AwStudio\Fjord\EloquentJs\EloquentJs($model, $class, $type))->toArray();
    }
}

if(! function_exists('is_translatable')) {
    function is_translatable($model)
    {
        $reflect = new \ReflectionClass($model);
        if ($reflect->implementsInterface('Astrotomic\Translatable\Contracts\Translatable')){
            return true;
        }
        return false;
    }
}

if(! function_exists('has_media')) {
    function has_media($model)
    {
        $reflect = new \ReflectionClass($model);
        if ($reflect->implementsInterface('Spatie\MediaLibrary\HasMedia\HasMedia')){
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

if(! function_exists('fjord_view')) {
    function fjord_view($name, $layout = false)
    {
        return $layout
            ? 'fjord::layouts.' . config('fjord.layout') . '.' . $name
            : 'fjord::' . $name;
    }
}

if(! function_exists('call_func')) {
    function call_func($method, array $params)
    {
        if(is_callable($method) && ! is_array($method)) {
            return call_user_func_array($method, $params);
        }

        if(is_array($method)) {
            $class = $method[0];
            $method = $method[1];

            return call_user_func_array([$class, $method], $params);
        }

        if(is_string($method)) {
            $split = explode('@', $method);
            $class = $split[0];
            $method = $split[1];

            return call_user_func_array([$class, $method], $params);
        }
    }
}

if(! function_exists('camel_space_case')) {
    function camel_space_case($string) {
        return collect(explode('_', Str::snake($string)))->map(function($item) {
            return ucfirst($item);
        })->implode(' ');
    }
}
