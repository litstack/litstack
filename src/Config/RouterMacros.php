<?php

namespace Ignite\Config;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

class RouterMacros
{
    /**
     * Register the macros.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfigMacro();
        $this->registerGetConfigMacro();
    }

    /**
     * Regsiter getConfig macro.
     *
     * @return void
     */
    protected function registerGetConfigMacro()
    {
        Route::macro('getConfig', function () {
            $key = $this->action['config'] ?? null;
            if (! $key) {
                return;
            }

            return lit()->config($key);
        });
    }

    /**
     * Register config macro.
     *
     * @return void
     */
    protected function registerConfigMacro()
    {
        RouteFacade::macro('config', function ($class) {
            $this->config = $class;

            if (isset($this->groupStack[0])) {
                $this->groupStack[0]['config'] = $this->config;
            }

            return $this;
        });

        Route::macro('config', function ($config) {
            $this->action['config'] = $config;

            return $this;
        });
    }
}
