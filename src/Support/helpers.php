<?php

use Fjord\Support\Facades\Vue;
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
     * @return \Fjord\Crud\CrudJs|Collection
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

        return new \Fjord\Crud\CrudJs($model);
    }
}

if (! function_exists('component')) {
    /**
     * Get a new Vue component instance.
     *
     * @param  \Fjord\Vue\Component|string $name
     * @param  string                      $fallback
     * @return \Fjord\Vue\Component|mixed
     */
    function component($name, $fallback = null)
    {
        if ($name instanceof \Fjord\Vue\Component) {
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

if (! function_exists('fjord_js')) {
    /**
     * Get the Fjord app.js file path.
     *
     * @return string
     */
    function fjord_js()
    {
        $js_path = config('fjord.assets.js')
            ? config('fjord.assets.js')
            : route('fjord.js');

        if (config('fjord.assets.js')) {
            $js_path .= '?v='.filemtime(ltrim(config('fjord.assets.js'), '/'));
        }

        return $js_path;
    }
}

if (! function_exists('fjord_css')) {
    /**
     * Get the Fjord app.css file path.
     *
     * @return string
     */
    function fjord_css()
    {
        return config('fjord.assets.app.css')
            ? config('fjord.assets.app.css')
            : route('fjord.css');
    }
}

if (! function_exists('fjord_user')) {
    /**
     * Get the authenticated Fjord user.
     *
     * @return \Fjord\User\Models\FjordUser
     */
    function fjord_user()
    {
        return Auth::guard('fjord')->user();
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

if (! function_exists('__f')) {
    /**
     * Translate by key.
     *
     * @param string $key
     * @param array  $replace
     *
     * @return void
     */
    function __f($key = null, $replace = [])
    {
        return fjord()->trans($key, $replace);
    }
}

if (! function_exists('__f_choice')) {
    /**
     * Translate choice by key.
     *
     * @param string $key
     * @param array  $replace
     *
     * @return void
     */
    function __f_choice($key, $number, $replace = [])
    {
        return fjord()->trans_choice($key, $number, $replace);
    }
}

if (! function_exists('__f_c')) {
    /**
     * Translate choice by key.
     *
     * @param string $key
     * @param array  $replace
     *
     * @return void
     */
    function __f_c($key, $number, $replace = [])
    {
        return fjord()->trans_choice($key, $number, $replace);
    }
}

if (! function_exists('__f_')) {
    /**
     * Translate if key exists or returns default.
     *
     * @param string $key
     * @param string $default
     * @param array  $replace
     *
     * @return string
     */
    function __f_($key, $default, $replace = [])
    {
        return __f($key, $replace) !== $key
            ? __f($key, $replace)
            : $default;
    }
}

if (! function_exists('fjord_config_path')) {
    /**
     * Path to Fjord config files.
     *
     * @param string $path
     *
     * @return void
     */
    function fjord_config_path($path = '')
    {
        return base_path('fjord/app/Config'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}

if (! function_exists('fjord_resource_path')) {
    /**
     * Path to Fjord resources.
     *
     * @param string $path
     *
     * @return void
     */
    function fjord_resource_path($path = '')
    {
        return base_path('fjord/resources'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}

if (! function_exists('fjord_path')) {
    /**
     * Path to Fjord composer package.
     *
     * @param string $path
     *
     * @return string
     */
    function fjord_path(string $path = '')
    {
        return realpath(__DIR__.'/../../').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('fjord')) {
    /**
     * Get Fjord facade.
     *
     * @return Fjord\Fjord\Fjord
     */
    function fjord()
    {
        return app()->get('fjord');
    }
}

if (! function_exists('fjord_app')) {
    /**
     * Get Fjord application instance.
     *
     * @param  string|null $abstract
     * @return mixed
     */
    function fjord_app($abstract = null)
    {
        $app = app()->get('fjord.app');

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
