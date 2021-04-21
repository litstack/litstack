<?php

use Ignite\Support\Facades\Vue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

if (! function_exists('production')) {
    /**
     * Determines whether app is running in production.
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
     * @param  mixed                          $model
     * @return \Ignite\Crud\CrudJs|Collection
     */
    function crud($model, $config)
    {
        if ($model instanceof Collection) {
            $cruds = collect([]);
            foreach ($model as $m) {
                $cruds[] = crud($m, $config);
            }

            return $cruds;
        }

        return new \Ignite\Crud\CrudJs($model, $config);
    }
}

if (! function_exists('component')) {
    /**
     * Get a new Vue component instance.
     *
     * @param  \Ignite\Vue\Component|string $name
     * @param  string                       $fallback
     * @return \Ignite\Vue\Component|mixed
     */
    function component($name, $fallback = null)
    {
        if ($name instanceof \Ignite\Vue\Component) {
            return $name;
        }

        if (is_subclass_of($name, \Ignite\Vue\Component::class)) {
            return new $name;
        }

        if (Vue::has($name) || is_null($fallback)) {
            return Vue::make($name);
        }

        return new $fallback($name);
    }
}

if (! function_exists('lit')) {
    /**
     * Get the Lit facade.
     *
     * @return \Ignite\Foundation\Litstack
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
     * @param  string|null                           $abstract
     * @return \Ignite\Applicaiton\Application|mixed
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

if (! function_exists('lit_user')) {
    /**
     * Get the authenticated Lit user.
     *
     * @return \Lit\Models\User
     */
    function lit_user()
    {
        return app(\Ignite\Application\Kernel::class)->user();
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
     * Get the path to the Litstack resources directory.
     *
     * @param  string $path
     * @return void
     */
    function lit_resource_path($path = '')
    {
        return lit()->resourcePath($path);
    }
}

if (! function_exists('lit_lang_path')) {
    /**
     * Get the path to the Litstack package language files.
     *
     * @param  string $path
     * @return string
     */
    function lit_lang_path(string $path = '')
    {
        return lit()->langPath($path);
    }
}

if (! function_exists('lit_path')) {
    /**
     * Get the path to the litstack "app" directory.
     *
     * @param  string $path
     * @return string
     */
    function lit_path(string $path = '')
    {
        return lit()->path($path);
    }
}

if (! function_exists('lit_base_path')) {
    /**
     * Get the base path of the "lit" folder.
     *
     * @param  string $path
     * @return string
     */
    function lit_base_path(string $path = '')
    {
        return lit()->basePath($path);
    }
}

if (! function_exists('lit_vendor_path')) {
    /**
     * Get the path to the litstack package vendor folder.
     *
     * @param  string $path
     * @return string
     */
    function lit_vendor_path(string $path = '')
    {
        return lit()->vendorPath($path);
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
        return lit()->transChoice($key, $number, $replace);
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
        return lit()->transChoice($key, $number, $replace);
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

if (! function_exists('lit_js')) {
    /**
     * Get the Lit app.js file path.
     *
     * @return string
     */
    function lit_js()
    {
        $js_path = config('lit.assets.app_js')
            ? config('lit.assets.app_js')
            : route('lit.js');

        if (config('lit.assets.app_js')) {
            $js_path .= '?v='.filemtime(ltrim(config('lit.assets.app_js'), '/'));
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
