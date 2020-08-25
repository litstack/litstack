<?php

use Ignite\Support\Facades\Vue;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (! function_exists('production')) {
    /**
     * Determines wether app is running in production.
     *
     * @return bool
     */
    function production()
    {
        return config('app.env') == 'production';
    }
}

if (! function_exists('debug')) {
    /**
     * Return default value in debug mode.
     *
     * @param  mixed $value
     * @return mixed
     */
    function debug($value)
    {
        if (! config('app.debug')) {
            return;
        }

        return value($value);
    }
}

if (! function_exists('crud')) {
    /**
     * Create new CrudJs instance.
     *
     * @param mixed $model
     *
     * @return \Ignite\Crud\CrudJs|Collection
     */
    function crud($model)
    {
        if ($model instanceof EloquentCollection && $model instanceof Collection) {
            $cruds = collect([]);
            foreach ($model as $m) {
                $cruds[] = crud($m);
            }

            return $cruds;
        }

        return new \Ignite\Crud\CrudJs($model);
    }
}

if (! function_exists('component')) {
    /**
     * Get a new Vue component instance.
     *
     * @param  \Ignite\Vue\Component|string $name
     * @param  string                      $fallback
     * @return \Ignite\Vue\Component|mixed
     */
    function component($name, $fallback = null)
    {
        if ($name instanceof \Ignite\Vue\Component) {
            return $name;
        }

        if (Vue::has($name) || is_null($fallback)) {
            return Vue::make($name);
        }

        return new $fallback($name);
    }
}

if (! function_exists('fa')) {
    /**
     * Get a fontawesome icon.
     *
     * @param string $group
     * @param string $icon
     *
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
     * @param string $string
     *
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
     * @param mixed $t
     *
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
     * @param string $path
     *
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

if (! function_exists('lit_js')) {
    /**
     * Get the Lit app.js file path.
     *
     * @return string
     */
    function lit_js()
    {
        $js_path = config('lit.assets.js')
            ? config('lit.assets.js')
            : route('lit.js');

        if (config('lit.assets.js')) {
            $js_path .= '?v='.filemtime(ltrim(config('lit.assets.js'), '/'));
        }

        return $js_path;
    }
}

if (! function_exists('lit_css')) {
    /**
     * Get the Lit app.css file path.
     *
     * @return string
     */
    function lit_css()
    {
        return config('lit.assets.app.css')
            ? config('lit.assets.app.css')
            : route('lit.css');
    }
}

if (! function_exists('lit_user')) {
    /**
     * Get the authenticated Lit user.
     *
     * @return \Ignite\User\Models\User
     */
    function lit_user()
    {
        return Auth::guard('lit')->user();
    }
}

if (! function_exists('asset_time')) {
    /**
     * Appends ?t={time} to files to disable caching.
     *
     * @return string
     */
    function asset_time()
    {
        return production() ? '' : '?t='.time();
    }
}

if (! function_exists('__lit')) {
    /**
     * Translate by key.
     *
     * @param string $key
     * @param array  $replace
     *
     * @return void
     */
    function __lit($key = null, $replace = [])
    {
        return lit()->trans($key, $replace);
    }
}

if (! function_exists('__lit_choice')) {
    /**
     * Translate choice by key.
     *
     * @param string $key
     * @param array  $replace
     *
     * @return void
     */
    function __lit_choice($key, $number, $replace = [])
    {
        return lit()->trans_choice($key, $number, $replace);
    }
}

if (! function_exists('__lit_c')) {
    /**
     * Translate choice by key.
     *
     * @param string $key
     * @param array  $replace
     *
     * @return void
     */
    function __lit_c($key, $number, $replace = [])
    {
        return lit()->trans_choice($key, $number, $replace);
    }
}

if (! function_exists('__lit_')) {
    /**
     * Translate if key exists or returns default.
     *
     * @param string $key
     * @param string $default
     * @param array  $replace
     *
     * @return string
     */
    function __lit_($key, $default, $replace = [])
    {
        return __lit($key, $replace) !== $key
            ? __lit($key, $replace)
            : $default;
    }
}

if (! function_exists('lit_config_path')) {
    /**
     * Path to Lit config files.
     *
     * @param string $path
     *
     * @return void
     */
    function lit_config_path($path = '')
    {
        return base_path('lit/app/Config'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}

if (! function_exists('lit_resource_path')) {
    /**
     * Path to Lit resources.
     *
     * @param string $path
     *
     * @return void
     */
    function lit_resource_path($path = '')
    {
        return base_path('lit/resources'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}

if (! function_exists('lit_path')) {
    /**
     * Path to Lit composer package.
     *
     * @param string $path
     *
     * @return string
     */
    function lit_path(string $path = '')
    {
        return realpath(__DIR__.'/../../').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('lit')) {
    /**
     * Get Lit facade.
     *
     * @return Lit\Foundation\Lit
     */
    function lit()
    {
        return app()->get('lit');
    }
}

if (! function_exists('lit_app')) {
    /**
     * Get Lit application instance.
     *
     * @param  string|null $abstract
     * @return mixed
     */
    function lit_app($abstract = null)
    {
        $app = app()->get('lit.app');

        if (! $abstract) {
            return $app;
        }

        return $app->get($abstract);
    }
}

if (! function_exists('is_translatable')) {
    /**
     * Is a Model translatable.
     *
     * @param string|mixed $model
     *
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
     * Does a Model has media.
     *
     * @param mixed $model
     *
     * @return bool
     */
    function has_media($model)
    {
        $reflect = new \ReflectionClass($model);
        if ($reflect->implementsInterface('Spatie\MediaLibrary\HasMedia\HasMedia')) {
            return true;
        }

        return false;
    }
}

if (! function_exists('is_valid_path')) {
    /**
     * Does a file exists.
     *
     * @param string $path
     *
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

if (! function_exists('medialibrary_config')) {
    /**
     * Spatie medialibrary changed its config file name.
     *
     * @return string
     */
    function medialibrary_config_key()
    {
        if (File::exists(config_path('medialibrary.php'))) {
            // For old versions.
            return 'medialibrary';
        }
        // For new versions.
        return 'media-library';
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
