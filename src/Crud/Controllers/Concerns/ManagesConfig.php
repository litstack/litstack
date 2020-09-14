<?php

namespace Ignite\Crud\Controllers\Concerns;

use Ignite\Config\ConfigHandler;
use Illuminate\Support\Facades\Request;

trait ManagesConfig
{
    /**
     * Config instance.
     *
     * @var \Ignite\Config\ConfigHandler
     */
    protected $config;

    /**
     * Set config manually.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    public function setConfig(ConfigHandler $config)
    {
        $this->config = $config;
    }

    /**
     * Get config.
     *
     * @return \Ignite\Config\ConfigHandler
     */
    protected function loadConfig()
    {
        if (! $route = Request::route()) {
            return;
        }

        return $route->getConfig();
    }

    /**
     * Get crud/form config.
     *
     * @return \Ignite\Config\ConfigHandler
     */
    public function getConfig()
    {
        return $this->config;
    }
}
