<?php

namespace FjordTest\Traits;

use Fjord\Config\ConfigHandler;
use Fjord\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\ForwardsCalls;

trait InteractsWithConfig
{
    /**
     * Get config mock.
     *
     * @param string $key
     * @return void
     */
    abstract public function getConfig(string $key, ...$params);

    /**
     * Determine if the config exsits.
     *
     * @return void
     */
    public function configExists(string $key)
    {
        return true;
    }

    /**
     * Override singleton "config.loader".
     *
     * @return void
     */
    public function overrideConfigLoaderSingleton()
    {
        $loader = new ConfigLoader(
            function (string $key) {
                return $this->configExists($key);
            },
            function ($key, ...$params) {
                return new ConfigHandler(
                    $this->getConfig($key, ...$params)
                );
            }
        );
        $this->app['fjord.app']->singleton('config.loader', $loader);
    }
}

class ConfigLoader
{
    public function __construct($exists, $loader)
    {
        $this->exists = $exists;
        $this->loader = $loader;
    }

    public function exists(string $key)
    {
        $exists = $this->exists;
        return $exists($key);
    }

    public function get($key, ...$params)
    {
        $loader = $this->loader;
        return $loader($key, ...$params);
    }

    public function getKey()
    {
        //
    }
}
