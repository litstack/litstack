<?php

if(! function_exists('fjord_resource_path')) {
    function fjord_resource_path($path = '') {
        return resource_path(config('fjord.resources_path') . ($path ? DIRECTORY_SEPARATOR.$path : $path));
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

if(! function_exists('form_collect')) {
    function form_collect(array $array) {
        return new \AwStudio\Fjord\Fjord\FormFields\FormFieldCollection($array);
    }
}

if(! function_exists('eloquentJs')) {
    function eloquentJs($model, $class) {
        return (new AwStudio\Fjord\EloquentJs\EloquentJs($model, $class))->toArray();
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

if(! function_exists('getConfig')) {
    function getConfig($type, $name)
    {
        $types = ['crud', 'page'];

        if(! in_array($type, $types)) {
            throw new \Exception("Invalid type: {$type}\nMust be one of the following types: " . implode(', ', $types));
        }

        $configFile = $type == 'page' ? 'fjord-pages' : 'fjord-crud';

        $translatable = null;
        $translatedAttributes = [];
        $fields = [];

        if($type == 'crud') {
            $translatable = is_translateable($name);

            $data = new $name();

            $translatedAttributes = $translatable ? $data->translatedAttributes() : null;

            $fields = config($configFile)[$data->getTable()];
        } else if($type == 'page') {
            $translatable = config($configFile)[$name]['translatable'];
            $translatedAttributes = collect(config($configFile)[$name]['fields'])->pluck('id');
            $fields = config($configFile)[$name]['fields'];
        }

        return collect(array_merge([
            'type' => $type,
            'page' => $name,
            'fields' => $fields,
            'repeatables' => config('fjord-repeatables'),
            'translatable' => $translatable,
            'translatedAttributes' => $translatedAttributes,
            'languages' => config('translatable.locales'),
        ]));
    }
}
