<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpCsFixer\ConfigInterface;

if (! function_exists('fa')) {
    /**
     * Get a fontawesome icon.
     *
     * @param  string $group
     * @param  string $icon
     * @return string
     */
    function fa(string $group, $icon = null)
    {
        if (! $icon) {
            $icon = $group;
            $group = 'fas';
        }

        return "<i class=\"{$group} fa-$icon\"></i>";
    }
}

if (! function_exists('strip_slashes')) {
    /**
     * Strip slashes for routes. Make /admin//route => /admin/route.
     *
     * @param  string $string
     * @return void
     */
    function strip_slashes(string $string)
    {
        return preg_replace('#/+#', '/', $string);
    }
}

if (! function_exists('is_closure')) {
    /**
     * Is Closure.
     *
     * @param  mixed $t
     * @return bool
     */
    function is_closure($t)
    {
        return $t instanceof Closure;
    }
}

if (! function_exists('split_path')) {
    /**
     * Split path.
     *
     * @param  string $path
     * @return string
     */
    function split_path(string $path)
    {
        if (Str::contains($path, '\\')) {
            return explode('\\', $path);
        }

        return explode('/', $path);
    }
}

if (! function_exists('is_translatable')) {
    /**
     * Is a Model translatable.
     *
     * @param  string|mixed $model
     * @return bool
     */
    function is_translatable($model)
    {
        if (is_string($model)) {
            $model = new $model();
        }

        $uses = array_keys(class_uses_recursive($model));

        if (in_array(\Astrotomic\Translatable\Translatable::class, $uses)) {
            return true;
        }

        return $model instanceof \Astrotomic\Translatable\Contracts\Translatable;
    }
}

if (! function_exists('is_attribute_translatable')) {
    /**
     * Is a Model attribute translatable.
     *
     * @param mixed  $model
     * @param string $attribute
     *
     * @return bool
     */
    function is_attribute_translatable(string $attribute, $model)
    {
        if (! is_translatable($model)) {
            return false;
        }

        if (is_string($model)) {
            $model = new $model();
        }

        return in_array($attribute, $model->translatedAttributes);
    }
}

if (! function_exists('has_media')) {
    /**
     * Determines if a model implements the HasMedia interface.
     *
     * @param  mixed $model
     * @return bool
     */
    function has_media($model)
    {
        return $model instanceof \Spatie\MediaLibrary\HasMedia;
    }
}

if (! function_exists('is_valid_path')) {
    /**
     * Does a file exists.
     *
     * @param  string $path
     * @return bool
     */
    function is_valid_path(string $path)
    {
        return (bool) file_exists($path);
    }
}

if (! function_exists('ph_cols')) {
    /**
     * Get ph cols from string length.
     *
     * @param string $path
     *
     * @return bool
     */
    function ph_cols(string $string, $max = 12)
    {
        $length = strlen($string);
        $cols = ceil($length / 2);
        if ($cols > $max) {
            return $max;
        }

        return $cols;
    }
}

if (! function_exists('call_unaccessible_method')) {
    /**
     * Calling protected or private class method.
     *
     * @param mixed|string $abstract
     * @param string       $method
     * @param array        $params
     *
     * @return mixed
     */
    function call_unaccessible_method($abstract, string $method, array $params = [])
    {
        $class = $abstract;
        if (! is_string($abstract)) {
            $class = get_class($abstract);
        }

        $class = new ReflectionClass($class);
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        if ($method->isStatic()) {
            return $method->invokeArgs(null, $params);
        }

        return $method->invokeArgs($abstract, $params);
    }
}

if (! function_exists('set_unaccessible_property')) {
    /**
     * Set protected or private class property value.
     *
     * @param mixed  $instance
     * @param string $property
     * @param mixed  $value
     *
     * @return void
     */
    function set_unaccessible_property($instance, string $property, $value)
    {
        $reflection = new ReflectionProperty(get_class($instance), $property);
        $reflection->setAccessible(true);
        $value = $reflection->setValue($instance, $value);
    }
}

if (! function_exists('get_unaccessible_property')) {
    /**
     * Set protected or private class property value.
     *
     * @param mixed  $instance
     * @param string $property
     * @param mixed  $value
     *
     * @return void
     */
    function get_unaccessible_property($object, string $property)
    {
        if (! is_string($object)) {
            $class = get_class($object);
        } else {
            $class = $object;
        }

        $reflection = new ReflectionProperty($class, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($object);
    }
}

if (! function_exists('fix_file')) {
    /**
     * Fixes the given file using php-cs-fixer.
     *
     * @param  string               $path
     * @param  ConfigInterface|null $config
     * @return void
     */
    function fix_file($path, ConfigInterface $config = null)
    {
        if (is_null($config)) {
            $config = require lit_vendor_path('fixer/.php_cs_config');
        }

        $rules = json_encode($config->getRules());

        $cmd = implode(' ', [
            base_path('vendor/bin/php-cs-fixer'), 'fix',
            $path, "--rules='{$rules}'",
            '--config='.lit_vendor_path('fixer/.php_cs_config'),
            '--allow-risky=yes',
            '2> /dev/null',
        ]);

        exec($cmd);
    }
}
