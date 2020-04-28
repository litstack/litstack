<?php

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;


if (!function_exists('crud')) {
    /**
     * Create new CrudJs instance.
     *
     * @param mixed $model
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

if (!function_exists('component')) {
    /**
     * Get a new Vue component instance.
     *
     * @param string $name
     * @param mixed $fallback
     * @return \Fjord\Vue\Component|mixed
     */
    function component(string $name, $fallback = null)
    {
        if (fjord()->get('components')->isRegistered($name)) {
            return fjord()->get('components')->component($name);
        }

        if ($fallback) {
            return new $fallback($name);
        }

        return new \Fjord\Vue\Component($name);
    }
}

if (!function_exists('fa')) {
    /**
     * Get a fontawesome icon.
     *
     * @param string $group
     * @param string $icon
     * @return string
     */
    function fa(string $group, $icon = null)
    {
        if (!$icon) {
            $icon = $group;
            $group = 'fas';
        }

        return "<i class=\"{$group} fa-$icon\"></i>";
    }
}

if (!function_exists('strip_slashes')) {
    /**
     * Strip slashes for routes. Make /admin//route => /admin/route
     *
     * @param string $string
     * @return void
     */
    function strip_slashes(string $string)
    {
        return preg_replace('#/+#', '/', $string);
    }
}

if (!function_exists('is_closure')) {
    /**
     * Is Closure.
     *
     * @param mixed $t
     * @return boolean
     */
    function is_closure($t)
    {
        return $t instanceof Closure;
    }
}

if (!function_exists('fjord_js')) {
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
            $js_path .= '?v=' . filemtime(ltrim(config('fjord.assets.js'), '/'));
        }

        return $js_path;
    }
}

if (!function_exists('fjord_user')) {
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

if (!function_exists('__f')) {
    /**
     * Translate by key.
     *
     * @param string $key
     * @param array $replace
     * @return void
     */
    function __f($key = null, $replace = [])
    {
        return fjord()->trans($key, $replace);
    }
}

if (!function_exists('__f_choice')) {
    /**
     * Translate choice by key.
     *
     * @param string $key
     * @param array $replace
     * @return void
     */
    function __f_choice($key = null, $number, $replace = [])
    {
        return fjord()->trans_choice($key, $number, $replace);
    }
}

if (!function_exists('__f_c')) {
    /**
     * Translate choice by key.
     *
     * @param string $key
     * @param array $replace
     * @return void
     */
    function __f_c($key = null, $number, $replace = [])
    {
        return fjord()->trans_choice($key, $number, $replace);
    }
}

if (!function_exists('__f_')) {
    /**
     * Translate if key exists or returns default.
     *
     * @param string $key
     * @param string $default
     * @param array $replace
     * @return string
     */
    function __f_($key, $default, $replace = [])
    {
        return __f($key, $replace) !== $key
            ? __f($key, $replace)
            : $default;
    }
}

if (!function_exists('fjord_config_path')) {
    /**
     * Path to Fjord config files.
     *
     * @param string $path
     * @return void
     */
    function fjord_config_path($path = '')
    {
        return base_path('fjord/app/Config' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}

if (!function_exists('fjord_resource_path')) {
    /**
     * Path to Fjord resources.
     *
     * @param string $path
     * @return void
     */
    function fjord_resource_path($path = '')
    {
        return base_path('fjord/resources' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}

if (!function_exists('fjord_path')) {
    /**
     * Path to Fjord composer package.
     *
     * @param string $path
     * @return string
     */
    function fjord_path(string $path = '')
    {
        return realpath(__DIR__ . '/../../') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('fjord')) {
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

if (!function_exists('fjord_app')) {
    /**
     * Get Fjord application instance.
     *
     * @return \Fjord\Application\Application
     */
    function fjord_app()
    {
        return app()->get('fjord.app');
    }
}

if (!function_exists('is_translatable')) {
    /**
     * Is a Model translatable.
     *
     * @param mixed $model
     * @return boolean
     */
    function is_translatable($model)
    {
        $reflect = new \ReflectionClass($model);
        if ($reflect->implementsInterface('Astrotomic\Translatable\Contracts\Translatable')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('has_media')) {
    /**
     * Does a Model has media.
     *
     * @param mixed $model
     * @return boolean
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

if (!function_exists('is_valid_path')) {
    /**
     * Does a file exists.
     *
     * @param string $path
     * @return boolean
     */
    function is_valid_path(string $path)
    {
        return (bool) file_exists($path);
    }
}
