<?php

namespace FjordTest\Traits;

use Fjord\Config\ConfigHandler;
use Fjord\Support\Facades\Config;

trait InteractsWithConfig
{
    /**
     * Get config mock.
     *
     * @param string $key
     *
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
        $this->app['fjord.app']->singleton('config.loader', function () {
            return new ConfigLoader(
                function (string $key) {
                    return $this->configExists($key);
                },
                function ($key, ...$params) {
                    return new ConfigHandler(
                        $this->getConfig($key, ...$params)
                    );
                }
            );
        });
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
